# Avalokitam

Avalokitam is a prosody analyzer for the Tamil language. By recognizing the prosodic patterns, the input verses are analyzed for all the six basic elements of Tamil prosody: eḻuttu (letter), acai (metreme), cīr (metrical foot), taḷai (linkage), aṭi (metrical line) & toṭai (ornamenation). The meter is then recognized by matching the elements with the elaborate and complex rules of Tamil prosody. The tool further provides an elaborate and user-friendly display of the prosodic analysis. It also has features to enable learning Tamil prosody.

It can detect the following metres:

vĕṇbā, āsiriyappā, taravugŏccagak kalippā, vĕṇgalippā, vañjippā, kuṟaṭṭāḻisai, kuṟaḷvĕṇsĕnduṟai, vĕṇḍāḻisai, vĕḷḷŏttāḻisai, vĕṇḍuṟai, vĕḷiviruttam, āsiriyattāḻisai, āsiriyattuṟai, āsiriyaviruttam, kalittāḻisai, kalittuṟai, kaṭṭaḷaikkalippā, kaṭṭaḷaikkalittuṟai, kaliviruttam, vañjittāḻisai, vañjittuṟai, vañjiviruttam

It is named upon Bodhisattva Avalokiteśvara, whom the Tamil Mahayana Buddhists of the yore considered as the progenitor of the Tamil language.

This is released under GNU AGPL 3.0 license.

The project can be accessed at http://www.avalokitam.com

அவலோகிதம் - ஒரு தமிழ் யாப்பு மென்பொருள் ஆகும். உள்ளிடப்பட்ட உரையினை தமிழ் யாப்பு விதிகளின் படி ஆராய்ந்து - எழுத்து, அசை, சீர், தளை, அடி, தொடை ஆகிய உறுப்புக்களை கணக்கிட்டு, இவற்றைக்கொண்டு உள்ளீட்டின் பாவகையினை கண்டறிந்து மேற்கூறிய யாப்புறுப்புகளையும் பாவிதிகளின் பொருத்தத்தையும் வெளியிடும். இதில் யாப்பிலக்கணம் கற்கவும் வழி உள்ளது. கீழ்க்கண்ட பாவகைகளை அவலோகிதம் கண்டுகொள்ளும் திறன் கொண்டது.

வெண்பா, ஆசிரியப்பா, தரவுகொச்சகக் கலிப்பா, வெண்கலிப்பா, வஞ்சிப்பா, குறட்டாழிசை, குறள்வெண்செந்துறை, வெண்டாழிசை, வெள்ளொத்தாழிசை, வெண்டுறை, வெளிவிருத்தம், ஆசிரியத்தாழிசை, ஆசிரியத்துறை, ஆசிரியவிருத்தம், கலித்தாழிசை, கலித்துறை, கட்டளைக்கலிப்பா, கட்டளைக்கலித்துறை, கலிவிருத்தம், வஞ்சித்தாழிசை, வஞ்சித்துறை, வஞ்சிவிருத்தம்

இதை யாப்பிலக்கணம் கற்கவும் பயன்படுத்திக்கொள்ளலாம்.

பண்டைய தமிழ் மஹாயான பௌத்தர்களால் தமிழ் மொழியை அகத்தியருக்கு உபதேசித்தவராக கருதப்பட்ட, சகலபுத்தர்களின் மஹாகருணையின் உருவகமாக விளங்கும் பகவான் போதிசத்துவர் அவலோகிதேஸ்வரரின் பெயர் இம்மென்பொருளுக்கு இடப்பட்டது.

# Development setup

## Quick start

```bash
./dev.sh
```

Starts all three services with colour-coded output:

| Service | URL | Notes |
|---------|-----|-------|
| PHP parser | `http://localhost:8080` | via Docker or local `php` |
| Node backend | `http://localhost:3001` | reads `backend/.env` |
| Quasar frontend | `http://localhost:9000` | HMR |

## Environment variables

Copy the example and fill in your key:

```bash
cp backend/.env.example backend/.env
```

| Variable | Description |
|----------|-------------|
| `GEMINI_API_KEY` | Gemini API key (required for AI features) |
| `GEMINI_MODEL` | Model name, e.g. `gemini-2.5-pro` |
| `PHP_API_URL` | URL of the Tamil prosody parser, e.g. `http://localhost:8080/api.php` |
| `PORT` | Node backend port (default `3001`) |
| `DEV_TOKEN` | Optional dev token to bypass AI usage limits |

`backend/.env` is gitignored — never commit it. For production (Render), set these in the Render dashboard.

## PHP parser

All parser calls go through `PHP_API_URL`. This covers:
- The AI generation/fix loop (`/ai/stream`)
- The VenpaaFixer constraint solver (`/venpa/suggest`)
- Integration tests

Locally the parser runs on port `8080` (Docker container or `php -S localhost:8080 -t phpbackend`). In production, point `PHP_API_URL` at your deployed parser.

## Running tests

From the `backend/` directory:

```bash
npm run test:unit         # 10 unit tests — no parser needed, runs in ~50ms
npm run test:integration  # 8 integration tests — requires PHP parser running
npm test                  # both
```

# GAE

COPY files from phpbackend to dist, compile the frontend and then deploy to GAE

#

