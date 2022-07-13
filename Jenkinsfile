pipeline {
  agent any {
  }
  stages {
    stage("Pushing Image to GCR") {
      script {
          sh "PYTHONUNBUFFERED=1 gcloud builds submit -t  gcr.io/gj-playground/frontend . "
        }
      }
  }
}
