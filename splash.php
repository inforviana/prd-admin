<?php
//ecra inicial
//
	
    if(isset($_GET['a']))
    {
        switch($_GET['a'])
        {
            case 'datatrabalho':
                setcookie('data_i',$_POST['data_i'],time()+3600);
                setcookie('data_f',$_POST['data_f'],time()+3600);
                break;
        }
    }
    
	if(isset(COOKIE['data_i']))
    {
        $data_i=$_COOKIE['data_i'];
        $data_f=$_COOKIE['data_f'];
    }else{
        $data_i=date('Y-m-d',strtotime('-1 month'));
        $data_f=date('Y-m-d');
    }
    
    echo '
        <script>
            $(function(){
                $("#data_i, #data_f").datepicker();
            });
        </script>
        
        <form method="POST" action="./index.php?a=datatrabalho">
            <table>
                <tr>
                    <td><input id="data_i" type="text" placeholder="data inicial" style="width:150px;text-align:center;font-size:18px;" value="'.$data_i.'"></td>
                    <td><input id="data_f" type="text" placeholder="data final" style="width:150px;text-align:center;font-size:18px;" value="'.$data_f.'"></td>
                    <td><input type="submit" value="OK"></td>
                </tr>
            </table>
        </form>
        ';
	require('mod_log_diario.php');
?>