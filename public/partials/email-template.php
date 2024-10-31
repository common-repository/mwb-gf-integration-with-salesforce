<?php
/**
 * Provide a public-facing email template for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Mwb_Gf_Integration_With_Salesforce
 * @subpackage Mwb_Gf_Integration_With_Salesforce/public/partials
 */

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Content-Type" content="text/html charset=UTF-8" >
		<title><?php echo esc_html__( 'Error - ', 'mwb-gf-integration-with-salesforce' ); ?><?php echo esc_html( ! empty( $data['title'] ) ? $data['title'] : '' ); ?></title>
	</head>
	<body>
		<table>
			<tr>
				<td style="font-family: sans-serif;background-color: #1f1f1f; height: 36px; color: #fff; font-size: 24px; padding: 0px 10px">
				<?php echo esc_html__( 'Error - ', 'mwb-gf-integration-with-salesforce' ); ?>
				<?php echo esc_html( $data['Title'] ); ?>
			</td>
			</tr>
			<tr>
				<td style="padding: 10px;">
					<table border="0" cellpadding="0" cellspacing="0" width="100%;">
						<tbody>    
							<?php foreach ( $data as $key => $value ) : ?>
								<?php if ( is_array( $value ) ) { ?>
									<?php foreach ( $value as $k => $v ) : ?>
										<?php if ( 'Logs' == $k ) : // @codingStandardsIgnoreLine ?>
											<tr>
												<td style="padding-top: 10px;color: #303030;font-family: Helvetica;font-size: 13px;line-height: 150%;text-align: right; font-weight: bold; width: 28%; padding-right: 10px;"><?php echo esc_html( $k ); ?></td>
												<td style="padding-top: 10px;color: #303030;font-family: Helvetica;font-size: 13px;line-height: 150%;text-align: left; word-break:break-all;">
													<a href="<?php echo esc_url( $v ); ?>" target="_blank"><?php echo esc_html__( 'View Logs', 'mwb-gf-integration-with-salesforce' ); ?></a>
												</td>
											</tr>
										<?php else : ?>
											<tr>
												<td style="padding-top: 10px;color: #303030;font-family: Helvetica;font-size: 13px;line-height: 150%;text-align: right; font-weight: bold; width: 28%; padding-right: 10px;"><?php echo esc_html( $k ); ?></td>
												<td style="padding-top: 10px;color: #303030;font-family: Helvetica;font-size: 13px;line-height: 150%;text-align: left; word-break:break-all;"><?php echo esc_html( $v ); ?></td>
											</tr>
										<?php endif; ?>   
									<?php endforeach; ?>	
								<?php } else { ?>
									<tr>
										<td style="padding-top: 10px;color: #303030;font-family: Helvetica;font-size: 13px;line-height: 150%;text-align: right; font-weight: bold; width: 28%; padding-right: 10px;"><?php echo esc_html( $key ); ?></td>
										<td style="padding-top: 10px;color: #303030;font-family: Helvetica;font-size: 13px;line-height: 150%;text-align: left; word-break:break-all;"><?php echo esc_html( $value ); ?></td>
									</tr> 
								<?php } ?>    
							<?php endforeach; ?>
						</tbody>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>

