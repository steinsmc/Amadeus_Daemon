#!/usr/bin/php
<?php
//Amadeus Builder By dhdj
namespace {
    //Package类使用抽象方法所以就直接编程抽象类了,无法被实例化但是可以被继承
    abstract class Package
    {
        //设置一些静态变量....
        private static $Base;
        private static $Timer;
        private static $TimerList = array();
        protected static $Build;
        protected static $LogPath;
        protected static $PharPath;
        protected static $BuildNum = null;
        public static $NoLog = false;
        public static $AsyncStatus = false;
        public static $BuildLog;
        public static $BuildTag;
        public static $BuildNumFile;

        //Package类的构造方法...这个得由子类自动
        public function __construct($BuildTag)
        {
            self::$Timer = gettimeofday();
            self::$Base = dirname(__FILE__) . '/';
            self::$Build = self::$Base . 'build/';
            self::$BuildLog = self::$Base . 'log/';
            self::$BuildTag = $BuildTag;
            self::$BuildNumFile = self::$Build . self::$BuildTag;
            self::getAsyncStatus();
            @mkdir(self::$Build);
            @mkdir(self::$BuildLog);
            self::$AsyncStatus ? $this->asyncGetBuildNum() : $this->syncGetBuildNum();
            /* 被抛弃的异步等待......
            while(is_null(self::$BuildNum)){
                usleep(50);
            }
            */
            self::$PharPath = self::$Build . 'Amadeus-Daemon-' . date("Ymdhis") . '-' . self::$BuildTag . '-Build-' . self::$BuildNum . '.phar';
            self::$LogPath = self::$BuildLog . self::$BuildTag . '-Build-' . self::$BuildNum . '.log';
            self::$AsyncStatus ? $this->asyncUpdateBuildNum() : $this->syncUpdateBuildNum();
        }

        //之前的构造方法只是为了初始化,packPhar才是真正的打包
        public function packPhar()
        {
            //这个Timer不用解释吧,计算时间的
            $this->syncTimer();
            $this->Phar = new Phar(self::$PharPath);
            $this->syncTimer();
            $this->Phar->buildFromDirectory(self::$Base . 'src');
            /*$this->Phar->buildFromIterator(
                new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator(self::$Base.'Service',FilesystemIterator::SKIP_DOTS)),
                self::$Base.'Service');
            */
            $this->syncTimer();
            $this->Phar->compressFiles(Phar::GZ);
            $this->syncTimer();
            $this->Phar->stopBuffering();
            $this->syncTimer();
            $this->Phar->setStub($this->Phar->createDefaultStub('AmadeusLoader.php', 'AmadeusLoader.php'));
            $this->syncTimer();
            $this->afterPharPacked();
            $this->endTimer();
            $this->printTimerStack();
            return true;
        }

        public static function deleteDir($Path)
        {
            if (substr($Path, -1) != DIRECTORY_SEPARATOR) {
                $Path = $Path . DIRECTORY_SEPARATOR;
            }
            $List = scandir($Path);
            foreach ($List as $Value) {
                if (!is_dir($Value)) {
                    unlink($Path . $Value);
                } elseif ($Value != '.' and $Value != '..') {
                    self::deleteDir($Path . $Value);
                }
            }
            @rmdir($Path);
        }

        //同步时间
        private function syncTimer()
        {
            self::$TimerList[] = round((gettimeofday()["usec"] - self::$Timer["usec"]) / 1000000 + gettimeofday()["sec"] - self::$Timer["sec"], 10);
            self::$Timer = gettimeofday();
            return true;
        }

        //结束时间统计
        private function endTimer()
        {
            self::$TimerList[] = round((gettimeofday()["usec"] - self::$Timer["usec"]) / 1000000 + gettimeofday()["sec"] - self::$Timer["sec"], 10);
            self::$TimerList[7] = (float)0;
            foreach (self::$TimerList as $Time) {
                if ($Time != self::$TimerList[7]) {
                    self::$TimerList[7] = self::$TimerList[7] + $Time;
                }
            }
            return true;
        }

        //输出TimerStack到Terminal并输出到文件中
        private function printTimerStack()
        {
            $TimerList = self::$TimerList;
            $Stack = <<<STACK

TimerStack(8) {
  [程序启动所用时间]=>
  float($TimerList[0])
  [文件创建所用时间]=>
  float($TimerList[1])
  [编译所用时间]=>
  float($TimerList[2])
  [Gzip压缩所用时间]=>
  float($TimerList[3])
  [停止所用时间]=>
  float($TimerList[4])
  [设置执行文件所用时间]=>
  float($TimerList[5])
  [程序执行所用时间]=>
  float($TimerList[6])
  [总耗时]=>
  float($TimerList[7])
}

STACK;
            if (!self::$NoLog) {
                file_put_contents(self::$LogPath, $Stack);
            };
            echo $Stack;
            unset($TimerList);
            unset($Stack);
            return true;
        }

        //同步方法获取Build号
        private function syncGetBuildNum()
        {
            if (file_exists(self::$BuildNumFile)) {
                self::$BuildNum = (int)file_get_contents(self::$BuildNumFile);
            } else {
                file_put_contents(self::$BuildNumFile, self::$BuildNum = (int)1);
            }
            return true;
        }

        //同步方法更新Build号
        private function syncUpdateBuildNum()
        {
            if (file_exists(self::$BuildNumFile)) {
                file_put_contents(self::$BuildNumFile, self::$BuildNum + 1);
            } else {
                file_put_contents(self::$BuildNumFile, self::$BuildNum = (int)1);
            }
            return true;
        }

        //异步方法获取Build号...不过因为技术问题变为同步了
        private function asyncGetBuildNum()
        {
            if (file_exists(self::$BuildNumFile)) {
                /*
                Swoole\Async::readFile(self::$BuildNumFile,function($Filename,$Content){
                    self::$BuildNum=(int)$Content;
                });
                */
                //这里swoole的Async方法不知道为什么回调出现了问题所以改成了同步读取,问题等未来再解决
                self::$BuildNum = (int)file_get_contents(self::$BuildNumFile);
            } else {
                Swoole\Async::writeFile(self::$BuildNumFile, self::$BuildNum = (int)1);
            }
            return true;
        }

        //异步更新Build号
        private function asyncUpdateBuildNum()
        {
            if (file_exists(self::$BuildNumFile)) {
                Swoole\Async::writeFile(self::$BuildNumFile, self::$BuildNum + 1);
            } else {
                Swoole\Async::writeFile(self::$BuildNumFile, self::$BuildNum = (int)1);
            }
            return true;
        }

        //获取PHP是否支持异步的信息
        private static function getAsyncStatus()
        {
            if (class_exists('Swoole\Async') and function_exists('swoole_async_writefile')) {
                self::$AsyncStatus = true;
                return true;
            } else {
                self::$AsyncStatus = false;
                return false;
            }
        }

        //抽象方法提供给子类
        public abstract function afterPharPacked();

    }

    //字面意思
    class Test extends Package
    {
        public function __construct()
        {
            parent::__construct('Test');
        }

        public function afterPharPacked()
        {
            echo "Memory: " . memory_get_usage() . "\r\n";
            system("php " . parent::$PharPath);
        }
    }

    //字面意思
    class Release extends Package
    {
        public function __construct()
        {
            parent::__construct('Release');
        }

        public function afterPharPacked()
        {
            echo "Memory: " . memory_get_usage() . "\r\n";
            echo "Released: " . parent::$PharPath;
        }
    }

    class Clean extends Package
    {
        public function __construct()
        {
            parent::$NoLog = true;
            parent::__construct("Clean");
        }

        public function afterPharPacked()
        {
            echo "Memory: " . memory_get_usage() . "\r\n";
            parent::deleteDir(parent::$BuildLog);
            parent::deleteDir(parent::$Build);
        }
    }

    switch (@$argv[1]) {
        case "Release":
        case "release":
        case "R":
        case "r":
            $Package = new \Release();
            break;
        case "C":
        case "Clean":
        case "c":
        case "clean":
            $Package = new \Clean();
            break;
        default:
            $Package = new \Test();
            break;
    }
    $Package->packPhar();
}