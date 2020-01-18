<template>
    <q-page padding>
    <div class="text-h5 q-mt-lg q-mb-md">Android</div>
     <div class="text-body1"> Coming soon </div>
    <!-- <div class="text-body1">You can download the android wrapper for the site in the <a href="https://play.google.com/store/apps/details?id=org.cordova.quasar.avalokitam">Play store</a>.</div>-->
    <div class="text-h5 q-mt-lg q-mb-md">Docker</div>
    <div class="text-body1">Avalokitam is now available as a docker container. Please follow the below steps to run Avalokitam locally in your system.</div>
    <div class="text-body1">
      <h5> Configure Docker </h5>
      <ul>
        <li> Install Docker for Windows from <a href="https://download.docker.com/win/stable/Docker%20for%20Windows%20Installer.exe">here</a>. In case the link is broken, please download it from <a href="https://docs.docker.com/docker-for-windows/install/">Dockerhub</a>.
        </li>
        <li> Run Docker Desktop after installation. There must be a Desktop icon for the same. Alternatively, search for Docker, and select Docker Desktop in the search results.
        </li>
        <li> Close the dialog that tells you this: Docker is already running and fully functional. It will also ask you to login. You can safely ignore it.</li>
        <li> Now you're all setup to use Docker. Docker will now start automatically with Windows.</li>

      </ul>
      <h5> Running Avalokitam </h5>
       <ul>
        <li> Download the <a href="http://virtualvinodh.com/aksharamkh/avalokitam-offline.zip">zip file</a> and extract it somewhere convenient. Do not separate the files. They should be in the same folder. </li>
        <li> Please make sure the whale icon in the status bar stays steady before proceeding (This ensures docker is up and running).
        <li> Windows might prevent you from running the below files. If that occurs, please select <i>More info</i> and click on <i>Run anyway</i>. </li>
        <li> Open  <i>install avalokitam</i> to install. It will attempt to download the necessary files and setup everything. This initial setup may last a while depending on your connection speed. Eventually, the screen will close after the installation.</li>
        <li> Once everything is installed, open <i>run avalokitam</i>. This should open a new browser window. Avalokitam is now available locally at: <i>http://localhost:54321</i>.</li>
        <li> If you want to stop Avalokitam, open <i>stop Avalokitam</i>.</li>
      </ul>

      <div class="text-h5 q-mt-lg q-mb-md"> Updating Avalokitam </div>

      <div class="text-body1">You can update the local copy of Avalokitam by clicking on the <i> install avalokitam </i> file again. This updates the local copy in case there are new updates. It is probabably useful to update your copy every couple of months at least.</div>
    </div>
  </q-page>
</template>

<script>
export default {
  // name: 'PageName',
  data () {
    return {
    }
  }
}
</script>

<style scoped>
.quote {
  text-align: left;
  margin-right: 0px;
  color: #777;
  font-style:italic;
  font-size:14px;
  line-height:20px;
}
</style>
