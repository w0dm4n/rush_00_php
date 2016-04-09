<?php
function redirect($where, $time)
{
	echo '<META http-equiv="refresh" content="'.$time.'; URL=?page='.$where.'">';
}