# How the AI works

## Architecture: Generate-validate-refine loop

Avalokitam's AI uses a **generate-validate-refine loop**: an LLM generates a candidate verse; a symbolic rule-based verifier (the Tamil prosody parser) formally validates them; structured feedback from the verifier is fed back to the LLM to guide the next attempt. The loop repeats until the verse is metrically valid or the attempt limit is reached.

This is sometimes called **verifier-in-the-loop generation**, related to the *Self-Refine* pattern (Madaan et al., 2023). The key distinction from self-critique is that the feedback comes from an external formal verifier — not the LLM judging its own output — which makes correctness guarantees possible.

```
┌─────────────────────────────────────────────────────┐
│                    runLoop()                        │
│                                                     │
│  topic / faulty verse                               │
│       │                                             │
│       ▼                                             │
│  ┌─────────┐   attempt 1–5   ┌──────────────────┐   │
│  │  Gemini │ ──────────────► │  PHP parser      │   │
│  │  (LLM)  │                 │  (Avalokitam)    │   │
│  └────▲────┘                 └────────┬─────────┘   │
│       │                               │             │
│       │   formatFeedback()            │ valid?      │
│       └───────────────────────────────┘             │
│                                               done  │
└─────────────────────────────────────────────────────┘
```

## Verse generation (`/ai/stream`, mode: generate)

1. **Prompt** — Gemini receives a system prompt describing வெண்பா metre rules plus the user's topic.
2. **Generate** — Gemini produces a candidate verse.
3. **Parse** — The PHP parser analyses the verse and returns an XML breakdown: foot names (சீர்), syllable types (நேர்/நிரை), linkage (தளை), line types (அளவடி/சிந்தடி), and metre detected.
4. **Validate** — `parseXML()` extracts violations: wrong foot types, wrong foot counts, வெண்டளை bond violations, missing lines.
5. **Feedback** — `formatFeedback()` converts those violations into precise natural-language instructions: which foot at which position is wrong, what it should be replaced with, and the minimum-change set of alternatives.
6. **Loop** — The feedback is appended to the next prompt. Gemini tries again. Up to **5 attempts**.
7. **Done** — On success, a second Gemini call generates a sandhi split, word-for-word literal, and meaning explanation.

## Verse fixing (`/ai/stream`, mode: fix)

Same loop as generation, except:
- The starting prompt includes the original faulty verse plus its parser errors.
- Gemini is asked to fix the verse rather than invent one from a topic.
- If the verse is already valid on first parse, the loop exits immediately.

## VenpaaFixer (`/venpa/suggest`) — no AI

The VenpaaFixer step-by-step composer does **not** call Gemini. It is a pure constraint solver:

1. The user builds the verse foot by foot (or word by word).
2. After each word, the PHP parser re-analyses the current state.
3. `getSuggestions()` / `getRunSuggestions()` compute which foot names are valid at each position given the bond rules (வெண்டளை), using a depth-first search over the 8 valid foot types.
4. The UI shows which syllable class the next word must start with and lists valid foot names.

This is **constraint propagation** — each placed foot constrains the next one deterministically.

## What the PHP parser provides

The parser is the symbolic half of the system. It applies the full rule set of classical Tamil prosody (*யாப்பிலக்கணம்*):

| Output | What it tells the AI |
|--------|----------------------|
| Foot name (சீர்) | Whether each word is a valid metre foot |
| Syllable type (நேர்/நிரை) | The exact weight pattern of each foot |
| Linkage (தளை) | Whether adjacent feet bond correctly (வெண்டளை) |
| Line type (அளவடி/சிந்தடி) | Whether each line has the right number of feet |
| Metre detected | The overall metre name |
| Rule violations | Any structural rule failures |

Without the parser, the LLM would have to hallucinate metre rules from training data — and would get them wrong. The parser makes the system **formally correct**: a verse only exits the loop when the symbolic verifier says it passes.

## Thinking budget (`GEMINI_THINKING_LEVEL`)

Gemini's extended thinking improves accuracy but increases latency and cost. The loop runs each attempt at the configured thinking level; the post-success explanation step always runs at `minimal`.

| Level | Behaviour |
|-------|-----------|
| `minimal` | Near-instant; good for simple verses |
| `low` | Slightly more careful word selection |
| `medium` | Noticeably better on complex metres |
| `high` | Best quality; slowest; use for difficult cases |

## Token cost

Each attempt consumes input tokens (prompt + feedback) and output tokens (generated verse + thinking). A typical successful run (2–3 attempts) costs roughly $0.001–0.005 at current Gemini Flash pricing. The backend logs per-call and per-run token counts.
