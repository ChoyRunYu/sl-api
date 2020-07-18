<?php
namespace app\api\controller\v1\admin;

use think\Db;
use think\Model;
use app\api\controller\v1\admin\common\BaseController;
use think\Route;
use think\Controller;

class SystemInfo extends BaseController
{
    public function getSystemInfo(){

        $rs = Db::query('select VERSION() as sqlversion');//获取数据库信息
        $res = [
            '服务器域名/IP地址'=>   ($_SERVER['SERVER_NAME'].'('.GetHostByName($_SERVER['SERVER_NAME']).')')?:'获取失败',
            '服务器标识'       =>   php_uname()?:'获取失败',
            '服务器操作系统'   =>   php_uname('s')?:'获取失败',
            '服务器架构'       =>   $_SERVER['SERVER_SOFTWARE']?:'获取失败',
            '服务器CPU核心数'  =>   getenv('NUMBER_OF_PROCESSORS')?:'获取失败',
            'PHP版本'         =>   PHP_VERSION?:'获取失败',
            'MYSQL数据库版本'  =>   $rs[0]['sqlversion']?:'获取失败',
            'THINKPHP版本'    =>   THINK_VERSION?:'获取失败',
        ];
        return ['code' => 200, 'msg' => '请求成功', 'data' => $res];
    }

    /**
     * 获取CPU和内存的使用率
     * @return array
     */
    public function getSystemUsage(){
        $cpuUse = $this->getCpuUsage();
        $memoryUse = $this->getMemoryUsage();
        $res = [
            'cpuUsage'  => (int)$cpuUse?:'获取失败',
            'useMemory' => ($memoryUse['totalMemory']-$memoryUse['freeMemory'])?:'获取失败',
            'freeMemory' => $memoryUse['freeMemory']?:'获取失败',
            'totalMemory' => $memoryUse['totalMemory']?:'获取失败',
            'memoryUsage' => $memoryUse['memoryUsage']?:'获取失败',
        ];
        return ['code' => 200, 'msg' => '请求成功', 'data' => $res];
    }

    /**
     * 获取磁盘使用情况
     * @return array
     */
    public function getDiskUsage(){
        $disk = $this->getHddUsage();
        return $disk?['code' => 200, 'msg' => '请求成功', 'data' => $disk]:
            ['code' => 404, 'msg' => '请求数据失败'];
    }


    /**
     * 判断指定路径下指定文件是否存在，如不存在则创建
     * @param string $fileName 文件名
     * @param string $content 文件内容
     * @return string 返回文件路径
     */
    private function getFilePath($fileName, $content)
    {
        $path = dirname(__FILE__) . "\\$fileName";
        if (!file_exists($path)) {
            file_put_contents($path, $content);
        }
        return $path;
    }

    /**
     * 获得cpu使用率vbs文件生成函数
     * @return string 返回vbs文件路径
     */
    private function getCpuUsageVbsPath()
    {
        return $this->getFilePath(
            'cpu_usage.vbs',
            "On Error Resume Next
            Set objProc = GetObject(\"winmgmts:\\\\.\\root\cimv2:win32_processor='cpu0'\")
            WScript.Echo(objProc.LoadPercentage)"
        );
    }

    /**
     * 获得总内存及可用物理内存JSON vbs文件生成函数
     * @return string 返回vbs文件路径
     */
    private function getMemoryUsageVbsPath()
    {
        return $this->getFilePath(
            'memory_usage.vbs',
            "On Error Resume Next
    Set objWMI = GetObject(\"winmgmts:\\\\.\\root\cimv2\")
    Set colOS = objWMI.InstancesOf(\"Win32_OperatingSystem\")
    For Each objOS in colOS
     Wscript.Echo(\"{\"\"TotalVisibleMemorySize\"\":\" & objOS.TotalVisibleMemorySize & \",\"\"FreePhysicalMemory\"\":\" & objOS.FreePhysicalMemory & \"}\")
    Next"
        );
    }


    /**
     * 获得CPU使用率
     * @return Number
     */
    public function getCpuUsage()
    {
        $path = $this->getCpuUsageVbsPath();
        exec("cscript -nologo $path", $usage);
        return $usage[0];
    }

    /**
     * 获得内存使用率数组
     * @return array
     */
    public function getMemoryUsage()
    {
        $path = $this->getMemoryUsageVbsPath();
        exec("cscript -nologo $path", $usage);
        $memory = json_decode($usage[0], true);
        $res = [
            'totalMemory'   =>  Round($memory['TotalVisibleMemorySize']/1024/1024, 3),
            'freeMemory'    =>  Round($memory['FreePhysicalMemory']/1024/1024, 3),
            'memoryUsage'   =>  Round((($memory['TotalVisibleMemorySize'] - $memory['FreePhysicalMemory']) / $memory['TotalVisibleMemorySize']) * 100)
        ];
        return $res;
    }

    /**
     * 获取磁盘空间使用情况
     * @return array
     */
    public function getHddUsage(){
        //获取磁盘信息、disk_x_space("y")的参数不能用变量,@在这里不起作用
        $diskct=0;
        $disk=array();
        $diskz=0; //磁盘总容量
        $diskk=0; //磁盘剩余容量
        $diskSign = ['B:','C:','D:','E:','F:','G:','H:','I:','J:','K:','L:'];//定义磁盘盘符
        //上面写了不能用变量，我查了下好像没查到，还是懒得找
        //虽然我写了一个用变量的版本好像也没问题，不过还是用了原作者写的n*个if的判断
//        foreach ($diskSign as $value){
//            if(@disk_total_space($value)!=NULL)
//            {
//                $diskct++;
//                $disk[rtrim($value, ':')][0]=round(@disk_free_space($value)/(1024*1024*1024),2);
//                $disk[rtrim($value, ':')][1]=round(@disk_total_space($value)/(1024*1024*1024),2);
//                $disk[rtrim($value, ':')][2]=round(((@disk_free_space($value)/(1024*1024*1024))/(@disk_total_space($value)/(1024*1024*1024)))*100,2).'%';
//                $diskk+=round((@disk_free_space($value)/(1024*1024*1024)),2);
//                $diskz+=round((@disk_total_space($value)/(1024*1024*1024)),2);
//            }
//        }
        if(@disk_total_space("C:")!=NULL)
        {
            $diskct++;
            $disk["C"]['free']=round((@disk_free_space("C:")/(1024*1024*1024)),2);
            $disk["C"]['total']=round((@disk_total_space("C:")/(1024*1024*1024)),2);
            $total = (@disk_total_space("C:")/(1024*1024*1024));
            $disk["C"]['usage']=round((($total-(@disk_free_space("C:")/(1024*1024*1024)))/(@disk_total_space("C:")/(1024*1024*1024)))*100,2);
            $disk['C']['use']=round((@disk_total_space("C:")/(1024*1024*1024))-(@disk_free_space("C:")/(1024*1024*1024)),2);
            $diskk+=round((@disk_free_space("C:")/(1024*1024*1024)),2);
            $diskz+=round((@disk_total_space("C:")/(1024*1024*1024)),2);
        }
        if(@disk_total_space("D:")!=NULL)
        {
            $diskct++;
            $disk["D"]['free']=round((@disk_free_space("D:")/(1024*1024*1024)),2);
            $disk["D"]['total']=round((@disk_total_space("D:")/(1024*1024*1024)),2);
            $total = (@disk_total_space("D:")/(1024*1024*1024));
            $disk["D"]['usage']=round((($total-(@disk_free_space("D:")/(1024*1024*1024)))/(@disk_total_space("D:")/(1024*1024*1024)))*100,2);
            $disk['D']['use'] = round((@disk_total_space('D:')/(1024*1024*1024))-(@disk_free_space('D:')/(1024*1024*1024)),2);
            $diskk+=round((@disk_free_space("D:")/(1024*1024*1024)),2);
            $diskz+=round((@disk_total_space("D:")/(1024*1024*1024)),2);
        }
        if(@disk_total_space("E:")!=NULL)
        {
            $diskct++;
            $disk["E"]['free']=round((@disk_free_space("E:")/(1024*1024*1024)),2);
            $disk["E"]['total']=round((@disk_total_space("E:")/(1024*1024*1024)),2);
            $total = (@disk_total_space("E:")/(1024*1024*1024));
            $disk["E"]['usage']=round((($total-(@disk_free_space("E:")/(1024*1024*1024)))/(@disk_total_space("E:")/(1024*1024*1024)))*100,2);
            $disk['E']['use'] = round((@disk_total_space('E:')/(1024*1024*1024))-(@disk_free_space('E:')/(1024*1024*1024)),2);
            $diskk+=round((@disk_free_space("E:")/(1024*1024*1024)),2);
            $diskz+=round((@disk_total_space("E:")/(1024*1024*1024)),2);
        }
        if(@disk_total_space("F:")!=NULL)
        {
            $diskct++;
            $disk["F"]['free']=round((@disk_free_space("F:")/(1024*1024*1024)),2);
            $disk["F"]['total']=round((@disk_total_space("F:")/(1024*1024*1024)),2);
            $total = (@disk_total_space("F:")/(1024*1024*1024));
            $disk["F"]['usage']=round((($total-(@disk_free_space("F:")/(1024*1024*1024)))/(@disk_total_space("F:")/(1024*1024*1024)))*100,2);
            $disk['F']['use'] = round((@disk_total_space('F:')/(1024*1024*1024))-(@disk_free_space('F:')/(1024*1024*1024)),2);
            $diskk+=round((@disk_free_space("F:")/(1024*1024*1024)),2);
            $diskz+=round((@disk_total_space("F:")/(1024*1024*1024)),2);
        }
        if(@disk_total_space("G:")!=NULL)
        {
            $diskct++;
            $disk["G"]['free']=round((@disk_free_space("G:")/(1024*1024*1024)),2);
            $disk["G"]['total']=round((@disk_total_space("G:")/(1024*1024*1024)),2);
            $total = (@disk_total_space("G:")/(1024*1024*1024));
            $disk["G"]['usage']=round((($total-(@disk_free_space("G:")/(1024*1024*1024)))/(@disk_total_space("G:")/(1024*1024*1024)))*100,2);
            $disk['G']['use'] = round((@disk_total_space('G:')/(1024*1024*1024))-(@disk_free_space('G:')/(1024*1024*1024)),2);
            $diskk+=round((@disk_free_space("G:")/(1024*1024*1024)),2);
            $diskz+=round((@disk_total_space("G:")/(1024*1024*1024)),2);
        }
        if(@disk_total_space("H:")!=NULL)
        {
            $diskct++;
            $disk["H"]['free']=round((@disk_free_space("H:")/(1024*1024*1024)),2);
            $disk["H"]['total']=round((@disk_total_space("H:")/(1024*1024*1024)),2);
            $total = (@disk_total_space("H:")/(1024*1024*1024));
            $disk["H"]['usage']=round((($total-(@disk_free_space("H:")/(1024*1024*1024)))/(@disk_total_space("H:")/(1024*1024*1024)))*100,2);
            $disk['H']['use'] = round((@disk_total_space('H:')/(1024*1024*1024))-(@disk_free_space('H:')/(1024*1024*1024)),2);
            $diskk+=round((@disk_free_space("H:")/(1024*1024*1024)),2);
            $diskz+=round((@disk_total_space("H:")/(1024*1024*1024)),2);
        }
        if(@disk_total_space("I:")!=NULL)
        {
            $diskct++;
            $disk["I"]['free']=round((@disk_free_space("I:")/(1024*1024*1024)),2);
            $disk["I"]['total']=round((@disk_total_space("I:")/(1024*1024*1024)),2);
            $total = (@disk_total_space("I:")/(1024*1024*1024));
            $disk["I"]['usage']=round((($total-(@disk_free_space("I:")/(1024*1024*1024)))/(@disk_total_space("I:")/(1024*1024*1024)))*100,2);
            $disk['I']['use'] = round((@disk_total_space('I:')/(1024*1024*1024))-(@disk_free_space('I:')/(1024*1024*1024)),2);
            $diskk+=round((@disk_free_space("I:")/(1024*1024*1024)),2);
            $diskz+=round((@disk_total_space("I:")/(1024*1024*1024)),2);
        }
        if(@disk_total_space("J:")!=NULL)
        {
            $diskct++;
            $disk["J"]['free']=round((@disk_free_space("J:")/(1024*1024*1024)),2);
            $disk["J"]['total']=round((@disk_total_space("J:")/(1024*1024*1024)),2);
            $total = (@disk_total_space("J:")/(1024*1024*1024));
            $disk["J"]['usage']=round((($total-(@disk_free_space("J:")/(1024*1024*1024)))/(@disk_total_space("J:")/(1024*1024*1024)))*100,2);
            $disk['J']['use'] = round((@disk_total_space('J:')/(1024*1024*1024))-(@disk_free_space('J:')/(1024*1024*1024)),2);
            $diskk+=round((@disk_free_space("J:")/(1024*1024*1024)),2);
            $diskz+=round((@disk_total_space("J:")/(1024*1024*1024)),2);
        }
        if(@disk_total_space("K:")!=NULL)
        {
            $diskct++;
            $disk["K"]['free']=round((@disk_free_space("K:")/(1024*1024*1024)),2);
            $disk["K"]['total']=round((@disk_total_space("K:")/(1024*1024*1024)),2);
            $total = (@disk_total_space("K:")/(1024*1024*1024));
            $disk["K"]['usage']=round((($total-(@disk_free_space("K:")/(1024*1024*1024)))/(@disk_total_space("K:")/(1024*1024*1024)))*100,2);
            $disk['K']['use'] = round((@disk_total_space('K:')/(1024*1024*1024))-(@disk_free_space('K:')/(1024*1024*1024)),2);
            $diskk+=round((@disk_free_space("K:")/(1024*1024*1024)),2);
            $diskz+=round((@disk_total_space("K:")/(1024*1024*1024)),2);
        }
        if(@disk_total_space("L:")!=NULL)
        {
            $diskct++;
            $disk["L"]['free']=round((@disk_free_space("L:")/(1024*1024*1024)),2);
            $disk["L"]['total']=round((@disk_total_space("L:")/(1024*1024*1024)),2);
            $total = (@disk_total_space("L:")/(1024*1024*1024));
            $disk["L"]['usage']=round((($total-(@disk_free_space("L:")/(1024*1024*1024)))/(@disk_total_space("L:")/(1024*1024*1024)))*100,2);
            $disk['L']['use'] = round((@disk_total_space('L:')/(1024*1024*1024))-(@disk_free_space('L:')/(1024*1024*1024)),2);
            $diskk+=round((@disk_free_space("L:")/(1024*1024*1024)),2);
            $diskz+=round((@disk_total_space("L:")/(1024*1024*1024)),2);
        }
        return $disk;
    }
}