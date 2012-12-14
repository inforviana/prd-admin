<?php
    /*
    *   GRAFICO CONSUMOS COMBUSTIVEL
    *
    */
?>
    <h3>Analise de Viaturas</h3>
    <style>
        #draggable { width:100px; height: 100px; padding: 0.5em; float: left; margin: 10px 10px 10px 0;};
        #droppable {width:150px; height: 150px; padding: 0.5em; float: left; margin: 10px;}
    </style>
    
    <script>
    $(function() {
        $( "#draggable" ).draggable();
        $( "#droppable" ).droppable({
            drop: function( event, ui ) {
                $( this )
                    .addClass( "ui-state-highlight" )
                    .find( "p" )
                        .html( "Dropped!" );
            }
        });
    });
    </script>
</head>
<body>
 
<div id="draggable" class="ui-widget-content">
    <p>Drag me to my target</p>
</div>
 
<div id="droppable" class="ui-widget-header">
    <p>Drop here</p>
</div>