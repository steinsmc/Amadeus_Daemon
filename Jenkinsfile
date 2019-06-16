pipeline {
    agent { docker { image 'composer' } }
    stages {
        stage('build') {
            steps {
                sh 'php --version'
            }
        }
    }
}
