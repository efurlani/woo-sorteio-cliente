<?php
/**
 * @package Woo_Sorteio_Cliente
 * @version 1.0
 */
/*
Plugin Name: Woo Sorteio Cliente
Plugin URI: https://www.webae.com.br/
Description: Selecione um ganhador automaticamente, ao clicar no botão 'Sorteio' o sistema encontra um cliente woocommerce. <a href="tools.php?page=woo-sorteio-cliente">Clique aqui</a>.
Author: Eric Furlani
Version: 1.0
Author URI: https://www.webae.com.br/
License: GPL2
*/

/*  Copyright 2019 Eric Furlani  (email : ericfurlani@webae.com.br)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action('admin_menu', 'wsc_menu');

function wsc_menu() {
	add_management_page('Sorteio Cliente', 'Woo Sorteio Cliente', 'manage_options', 'woo-sorteio-winner', 'wsc_options');
}

function wsc_options() {
	global $wpdb;
	
	if (!current_user_can('manage_options'))  {
		wp_die( __('Você não tem permissão para acessar esse plugin.') );
	}

	
	if( is_numeric($_POST['wsc-num-winners']) ) {

			
		$winners = $wpdb->get_results($wpdb->prepare("SELECT ID, user_nicename, user_email FROM $wpdb->users GROUP BY user_email ORDER BY RAND() LIMIT %d", $_POST['wsc-num-winners'] ));
		
		$winners_text="";
		
		foreach ($winners as $winner) {
			
			$winners_text .= "<p>O Vencedor é: $winner->user_nicename: <a href='mailto:$winner->user_email'>Enviar e-mail cliente ?</a> <a href='mailto:$winner->ID'>Ver Perfil</a></p>\n";
		}

		echo $winners_text;

	} 


	
	if (is_numeric($_POST['wsc-num-winners'])) {
		$saved_num_winners = $_POST['wsc-num-winners'];
	}

?>
  <div class="wrap">
  	<h2>Woo Sorteio Cliente</h2>
  	<p>Quantidade que clientes que deseja contemplar?</p>
  	
  	<form name="wsc_form" id="wsc_form" action="" method="post">
			
		<p><label>Quantidade de Vencedores?</label>
			<select name="wsc-num-winners">
				<?php wsc_get_number_winners_dropdown($saved_num_winners); ?>
			</select>
		</p>
		<p><input type="submit" value="Sorteio!" class="button button-primary" ></p>
  	</form>
  </div>
<?php 
}


function wsc_get_number_winners_dropdown($num_winners) {
	$num_winner_options ="";
	for($i = 1; $i <= 50; $i++) {
		$selected = '';
		if ($num_winners==$i) {
			$selected .= " selected";
		}

		$num_winner_options .= sprintf("<option%s>%s</option>\n", $selected, $i);
	}
	
	echo $num_winner_options;
}
?>
