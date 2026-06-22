docker run -d -p 8080:8080 --name avalokitam virtualvinodh/avalokitam:latest
timeout /t 3 >nul
start "" http://localhost:8080
