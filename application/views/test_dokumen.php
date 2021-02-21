<?php  
	$cab = $cbs->row();
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="<?php  echo base_url();?>css/basil.css">
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
            
			<table>
				<tr>
					<td><img src="<?php  echo base_url();?>img/logokop.png" width="80" height="80"/></td>
					<td valign="top" class="headtitle">
					<h3 class="ksptitle"><?php  echo $cab->NAMAKSP?></h3>
					<?php  echo $cab->ALAMAT?> <?php  echo $cab->KOTA?><br>
					Telp : <?php  echo $cab->TELP?> Email : <?php  echo $cab->EMAIL?><br>
					Web : <?php  echo $cab->WEB?>
					</td>
				</tr>
			</table>
        </header>

        <footer>
           
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            <h1>Hello World</h1>
        </main>
    </body>
</html>
