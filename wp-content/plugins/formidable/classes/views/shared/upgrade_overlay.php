<div id="frm_upgrade_modal" class="frm_hidden settings-lite-cta">
	<div class="metabox-holder">
		<div class="postbox">
			<a href="#" class="dismiss alignright" title="<?php esc_attr_e( 'Dismiss this message', 'formidable' ); ?>"><i class="dashicons dashicons-no-alt" aria-label="Dismiss" aria-hidden="true"></i></a>
			<div class="inside">

				<i class="dashicons dashicons-lock"></i>
				<h2>
					<?php
					printf(
						esc_html__( '%s are not installed', 'formidable' ),
						'<span class="frm_feature_label"></span>'
					);
					?> 
				</h2>
				<div class="cta-inside">

					<p id="frm-oneclick" class="frm_hidden">
						<?php esc_html_e( 'That add-on is not installed. Would you like to install it now?', 'formidable' ); ?>
					</p>
					<p id="frm-addon-status"></p>

					<a class="button button-primary frm-button-primary frm_hidden" id="frm-oneclick-button">
						<?php esc_html_e( 'Install', 'formidable' ); ?>
					</a>

					<p id="frm-upgrade-message">
						<?php
						if ( $is_pro ) {
							$message = __( '%s are not available on your plan. Please upgrade or renew your license to unlock more awesome features.', 'formidable' );
						} else {
							$message = __( '%s are not available on your plan. Did you know you can upgrade to PRO to unlock more awesome features?', 'formidable' );
						}
						printf( esc_html( $message ), '<span class="frm_feature_label"></span>' );
						?>
					</p>
					<?php if ( $is_pro ) { ?>
						<a href="<?php echo esc_url( FrmAppHelper::admin_upgrade_link( $upgrade_link ) ); ?>" class="button button-primary frm-button-primary" id="frm-upgrade-modal-link">
								<?php esc_html_e( 'Upgrade', 'formidable' ); ?>
						</a>
					<?php } else { ?>
						<a href="<?php echo esc_url( FrmAppHelper::admin_upgrade_link( $upgrade_link ) ); ?>" class="button button-primary frm-button-primary" target="_blank" rel="noopener noreferrer" id="frm-upgrade-modal-link">
							<?php esc_html_e( 'Upgrade to Pro', 'formidable' ); ?>
						</a>

						<p>
							<a href="<?php echo esc_url( FrmAppHelper::make_affiliate_url( FrmAppHelper::admin_upgrade_link( $upgrade_link, 'knowledgebase/install-formidable-forms/' ) ) ); ?>" target="_blank" class="frm-link-secondary">
								<?php esc_html_e( 'Already purchased?', 'formidable' ); ?>
							</a>
						</p>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
