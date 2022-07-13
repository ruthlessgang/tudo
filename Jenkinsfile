pipeline {
  agent any 
  stages {
    stage('Build and push') {
      steps {
          sh "PYTHONUNBUFFERED=1 gcloud builds submit -t  gcr.io/gj-playground/bsstudo . "
        }
      }
  }
}
