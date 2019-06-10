pipeline {
    agent { docker { image 'composer' } }
    stages {
        stage('build') {
            steps {
                sh 'php --version'
                sh 'pecl install swoole'
                sh 'pecl install yaml'
                sh 'pecl install uuid'
                sh 'composer install'
                sh './build.sh'
            }
        }
    }
}
