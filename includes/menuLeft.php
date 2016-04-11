<?php
	echo '<DIV id="left_frame">
			<DIV class="menu">
				<UL>
					<LI><A href="?page=home">Home</A>
					</LI><BR/>
					<LI><A href="?page=category">Categories</A>					
					</LI><BR/>';
					if (empty($_SESSION["account"]))
						echo '<LI><A href="?page=register">Inscription</A></LI><BR/>';	
				echo '<LI><A href="?page=vitrine">Vitrine</A></LI>	</UL>
				</DIV>
			</DIV>';
?>