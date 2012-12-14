<?php
//ecra inicial
//
	
	
    
    echo '
        <script>
            $(function(){
                $("#datai #data_f").datepicker();
            });
        </script>
        
        <form method="POST" action="./index.php?a=datatrabalho">
            <table>
                <tr>
                    <td><input id="data_i" type="text" placeholder="data inicial" style="width:150px;text-align:center;font-size:18px;"></td>
                    <td><input id="data_f" type="text" placeholder="data final" style="width:150px;text-align:center;font-size:18px;"></td>
                </tr>
            </table>
        </form>
        ';
	require('mod_log_diario.php');
?>