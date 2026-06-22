<template>
  <q-page padding>
    <div class="text-h5 q-mt-lg q-mb-md">Android</div>
    <div class="text-body1">Coming soon</div>

    <div class="text-h5 q-mt-lg q-mb-md">Docker (Offline)</div>
    <div class="text-body1 q-mb-md">
      Run Avalokitam locally using Docker. The full app — prosody analyser, VenpaaFixer, and AI generation — runs in a single container on your machine.
    </div>

    <q-tabs v-model="tab" align="left" class="q-mb-md">
      <q-tab name="windows" label="Windows" />
      <q-tab name="mac" label="Mac / Linux" />
    </q-tabs>

    <q-tab-panels v-model="tab" animated>

      <!-- ── Windows ── -->
      <q-tab-panel name="windows">
        <div class="text-h6 q-mb-sm">1. Install Docker Desktop</div>
        <div class="text-body1 q-mb-md">
          Download and install <a href="https://docs.docker.com/desktop/install/windows-install/" target="_blank">Docker Desktop for Windows</a>.
          After installation, launch Docker Desktop and wait for the whale icon in the taskbar to become steady.
        </div>

        <div class="text-h6 q-mb-sm">2. Download the batch files</div>
        <div class="text-body1 q-mb-md">
          Download <a href="statics/avalokitam-windows.zip">avalokitam-windows.zip</a> and extract the three <code>.bat</code> files to any folder.
        </div>

        <div class="text-h6 q-mb-sm">3. Run</div>
        <ol class="text-body1">
          <li>Double-click <strong>install avalokitam.bat</strong> — downloads the image (one time only, ~500 MB).</li>
          <li>Double-click <strong>run avalokitam.bat</strong> — starts the app and opens your browser at <a href="http://localhost:8080" target="_blank">http://localhost:8080</a>.</li>
          <li>When done, double-click <strong>stop avalokitam.bat</strong> to shut it down.</li>
        </ol>

        <q-banner class="bg-grey-2 q-mt-md" rounded>
          <template v-slot:avatar><q-icon name="info" color="primary" /></template>
          <strong>AI features</strong> (AI verse generation, VenpaaFixer AI) require a free
          <a href="https://aistudio.google.com/apikey" target="_blank">Gemini API key</a>.
          Open <strong>run avalokitam.bat</strong> in a text editor and add these to the <code>docker run</code> line:
          <pre class="code-block q-mt-sm">-e GEMINI_API_KEY=your_key_here -e GEMINI_THINKING_LEVEL=minimal</pre>
          <code>GEMINI_THINKING_LEVEL</code> controls AI quality vs. speed: <code>minimal</code> (fastest), <code>low</code>, <code>medium</code>, <code>high</code> (most thorough).
        </q-banner>
      </q-tab-panel>

      <!-- ── Mac / Linux ── -->
      <q-tab-panel name="mac">
        <div class="text-h6 q-mb-sm">1. Install Docker</div>
        <div class="text-body1 q-mb-md">
          <strong>Mac:</strong> Download <a href="https://docs.docker.com/desktop/install/mac-install/" target="_blank">Docker Desktop for Mac</a>.<br>
          <strong>Linux:</strong> Follow the <a href="https://docs.docker.com/engine/install/" target="_blank">Docker Engine install guide</a> for your distro.
        </div>

        <div class="text-h6 q-mb-sm">2. Run</div>
        <div class="text-body1 q-mb-sm">Open a terminal and run:</div>
        <pre class="code-block">docker run -d --name avalokitam -p 8080:8080 virtualvinodh/avalokitam:latest</pre>
        <div class="text-body1 q-mt-sm q-mb-md">
          Then open <a href="http://localhost:8080" target="_blank">http://localhost:8080</a> in your browser.
        </div>

        <div class="text-h6 q-mb-sm">3. Stop</div>
        <pre class="code-block">docker stop avalokitam &amp;&amp; docker rm avalokitam</pre>

        <div class="text-h6 q-mt-lg q-mb-sm">Updating</div>
        <div class="text-body1 q-mb-sm">To get the latest version:</div>
        <pre class="code-block">docker pull virtualvinodh/avalokitam:latest</pre>

        <q-banner class="bg-grey-2 q-mt-md" rounded>
          <template v-slot:avatar><q-icon name="info" color="primary" /></template>
          <strong>AI features</strong> (AI verse generation, VenpaaFixer AI) require a free
          <a href="https://aistudio.google.com/apikey" target="_blank">Gemini API key</a>.
          Pass it along with the thinking level:
          <pre class="code-block q-mt-sm">docker run -d --name avalokitam -p 8080:8080 \
  -e GEMINI_API_KEY=your_key_here \
  -e GEMINI_THINKING_LEVEL=minimal \
  virtualvinodh/avalokitam:latest</pre>
          <code>GEMINI_THINKING_LEVEL</code>: <code>minimal</code> (fastest), <code>low</code>, <code>medium</code>, <code>high</code> (most thorough).
        </q-banner>
      </q-tab-panel>

    </q-tab-panels>
  </q-page>
</template>

<script>
export default {
  data () {
    return {
      tab: 'windows'
    }
  }
}
</script>

<style scoped>
.code-block {
  background: #f5f5f5;
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 12px 16px;
  font-family: monospace;
  font-size: 13px;
  white-space: pre-wrap;
  word-break: break-all;
}
</style>
