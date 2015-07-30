<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$FAQ = plang("FAQ");
$faq1 = plang("faq1");
$faq2 = plang("faq2");
$faq3 = plang("faq3");
$faq4 = plang("faq4");
$faq5 = plang("faq5");
$ans1 = plang("ans1");
$ans2 = plang("ans2");
$ans3 = plang("ans3");
$ans4 = plang("ans4");
$ans5 = plang("ans5");

$update = plang("update");
$update_node = plang("update_node");


$html = <<<HTML
		<table class="tb tb2 ">
			<tbody>
			<tr>
				<th colspan="15" class="partition">$FAQ
				</th>
			</tr>
			<tr class="noborder" >
				<td class="vtop tips2" s="1">
					<ul>
						<li>
							<p>$faq1</p>
							<p>$ans1</p><br/>
						</li>
						<li>
							<p>$faq2</p>
							<p>$ans2</p><br/>
						</li>
						<li>
							<p>$faq3</p>
							<p>$ans3</p><br/>
						</li>
						<li>
							<p>$faq4</p>
							<p>$ans4</p><br/>
						</li>
						<li>
							<p>$faq5</p>
							<p>$ans5</p><br/>
						</li>
					</ul>

					$FAQ_note
				</td>				
			</tr>
			<tr>
				<th colspan="15" class="partition">$update
				</th>

			</tr>
			<tr>
				<td>
					$update_node
				</td>
			</tr>
			</tbody>
		</table>
HTML;
echo $html;	


function plang($str) {
	return lang('plugin/geetest', $str);
}

?>