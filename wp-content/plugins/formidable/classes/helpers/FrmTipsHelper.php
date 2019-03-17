<?php

class FrmTipsHelper {

	public static function pro_tip( $callback, $html = '' ) {
		if ( FrmAppHelper::pro_is_installed() ) {
			return;
		}

		$tips = self::$callback();
		$tip = self::get_random_tip( $tips );

		if ( 'p' === $html ) {
			echo '<p>';
		}

		if ( ! isset( $tip['page'] ) ) {
			$tip['page'] = '';
		}
		if ( ! isset( $tip['link']['medium'] ) ) {
			$tip['link']['medium'] = 'tip';
		}

		$link = FrmAppHelper::make_affiliate_url( FrmAppHelper::admin_upgrade_link( $tip['link'], $tip['page'] ) );
		?>
		<a href="<?php echo esc_url( $link ); ?>" target="_blank" class="frm_pro_tip">
			<span>
				<i class="frm_icon_font frm_check1_icon"></i>  <?php esc_html_e( 'Pro Tip:', 'formidable' ); ?>
			</span>
			<?php echo esc_html( $tip['tip'] ) ?>
			<?php if ( isset( $tip['call'] ) ) { ?>
				<span><?php echo esc_html( $tip['call'] ) ?></span>
			<?php } ?>
		</a>
		<?php
		if ( 'p' === $html ) {
			echo '</p>';
		}
	}

	public static function get_builder_tip() {
		$tips = array(
			array(
				'link' => array(
					'content' => 'conditional-logic',
					'anchor'  => 'feature-conditional-logic-wordpress-forms',
				),
				'tip'  => __( 'Use conditional logic to shorten your forms and increase conversions.', 'formidable' ),
				'call' => __( 'Upgrade to Pro.', 'formidable' ),
			),
			array(
				'link' => array(
					'content' => 'page-breaks',
					'anchor'  => 'feature-wordpress-multi-step-form',
				),
				'tip'  => __( 'Stop intimidating users with long forms.', 'formidable' ),
				'call' => __( 'Use page breaks.', 'formidable' ),
			),
			array(
				'link' => array(
					'content' => 'file-uploads',
					'anchor'  => 'feature-wordpress-multiple-file-upload-form',
				),
				'tip'  => __( 'Cut down on back-and-forth with clients.', 'formidable' ),
				'call' => __( 'Allow file uploads in your form.', 'formidable' ),
			),
			array(
				'link' => array(
					'content' => 'calculations',
					'anchor'  => 'feature-wordpress-calculated-fields-form',
				),
				'tip'  => __( 'Need to calculate a total?', 'formidable' ),
				'call' => __( 'Upgrade to Pro.', 'formidable' ),
			),
			array(
				'link' => array(
					'content' => 'prefill-fields',
					'anchor'  => 'feature-fill-out-forms-automatically',
				),
				'tip'  => __( 'Save time.', 'formidable' ),
				'call' => __( 'Prefill fields with user info.', 'formidable' ),
			),
		);

		return $tips;
	}

	public static function get_form_settings_tip() {
		$tips = array(
			array(
				'link' => array(
					'content' => 'front-edit',
					'anchor'  => 'feature-front-end-editing-wordpress',
				),
				'tip'  => __( 'A site with dynamic, user-generated content is within reach.', 'formidable' ),
				'call' => __( 'Add front-end editing.', 'formidable' ),
			),
			array(
				'link' => array(
					'content' => 'front-edit-b',
					'anchor'  => 'feature-user-submitted-posts-wordpress-forms',
				),
				'tip'  => __( 'A site with dynamic, user-generated content is within reach.', 'formidable' ),
				'call' => __( 'Add front-end editing.', 'formidable' ),
			),
			array(
				'link' => array(
					'content' => 'save-drafts',
					'anchor'  => 'feature-user-submitted-posts-wordpress-forms',
				),
				'tip'  => __( 'Have a long form that takes time to complete?', 'formidable' ),
				'call' => __( 'Let logged-in users save a draft and return later.', 'formidable' ),
			),
			array(
				'link' => array(
					'content' => 'ajax',
				),
				'tip'  => __( 'Want to submit forms without reloading the page?', 'formidable' ),
				'call' => __( 'Get ajax form submit.', 'formidable' ),
			),
		);
		return $tips;
	}

	public static function get_form_action_tip() {
		$tips = array(
			array(
				'link' => array(
					'content' => 'email-routing',
					'anchor'  => 'feature-email-autoresponders-wordpress',
				),
				'tip'  => __( 'Save time by sending the email to the right person automatically.', 'formidable' ),
				'call' => __( 'Add email routing.', 'formidable' ),
			),
			array(
				'link' => array(
					'content' => 'create-posts',
					'anchor'  => 'feature-user-submitted-posts-wordpress-forms',
				),
				'tip'  => __( 'Create blog posts or pages from the front-end.', 'formidable' ),
				'call' => __( 'Upgrade to Pro.', 'formidable' ),
			),
			array(
				'link' => array(
					'content' => 'user-submit',
					'anchor'  => 'feature-user-submitted-posts-wordpress-forms',
				),
				'tip'  => __( 'Make front-end posting easy.', 'formidable' ),
				'call' => __( 'Upgrade to Pro.', 'formidable' ),
			),
			array(
				'link' => array(
					'content' => 'mailchimp',
					'page'    => 'mailchimp-tip',
				),
				'tip'  => __( 'Grow your business with automated email follow-up.', 'formidable' ),
				'call' => __( 'Send leads straight to MailChimp.', 'formidable' ),
			),
			array(
				'link' => array(
					'content' => 'paypal',
					'page'    => 'paypal-tip',
				),
				'tip'  => __( 'Save hours and increase revenue by collecting payments with every submission.', 'formidable' ),
				'call' => __( 'Use PayPal with this form.', 'formidable' ),
			),
			array(
				'link' => array(
					'content' => 'paypal-revenue',
					'page'    => 'paypal-increase-revenue-tip',
				),
				'tip'  => __( 'Increase revenue.', 'formidable' ),
				'call' => __( 'Use PayPal with this form.', 'formidable' ),
			),
			array(
				'link' => array(
					'content' => 'paypal-fast',
					'page'    => 'paypal-save-time-tip',
				),
				'tip'  => __( 'Get paid more quickly.', 'formidable' ),
				'call' => __( 'Use Paypal with this form.', 'formidable' ),
			),
			array(
				'link' => array(
					'content' => 'registration',
					'page'    => 'registration-tip',
				),
				'tip'  => __( 'Boost your site membership.', 'formidable' ),
				'call' => __( 'Automatically create user accounts.', 'formidable' ),
			),
			array(
				'link' => array(
					'content' => 'profile',
					'page'    => 'registration-profile-editing-tip',
				),
				'tip'  => __( 'Make front-end profile editing possible.', 'formidable' ),
				'call' => __( 'Add user registration.', 'formidable' ),
			),
			array(
				'link' => array(
					'content' => 'twilio-payment',
					'page'    => 'twilio-tip',
				),
				'tip'  => __( 'Want a text when this form is submitted or when a payment is received?', 'formidable' ),
				'call' => __( 'Use Twilio with this form.', 'formidable' ),
			),
			array(
				'link' => array(
					'content' => 'twilio',
					'page'    => 'twilio-send-tip',
				),
				'tip'  => __( 'Send a text when this form is submitted.', 'formidable' ),
				'call' => __( 'Get Twilio.', 'formidable' ),
			),
		);

		return $tips;
	}

	public static function get_styling_tip() {
		$tips = array(
			array(
				'link' => array(
					'content' => 'style',
					'anchor'  => 'feature-wordpress-visual-form-styler',
				),
				'tip'  => __( 'Make your sidebar or footer form stand out.', 'formidable' ),
				'call' => __( 'Use multiple style templates.', 'formidable' ),
			),
		);
		return $tips;
	}

	public static function get_entries_tip() {
		$tips = array(
			array(
				'link' => array(
					'content' => 'entries',
					'anchor'  => 'feature-form-entry-management-wordpress',
				),
				'tip'  => __( 'Want to edit or delete form submissions?', 'formidable' ),
				'call' => __( 'Add entry management.', 'formidable' ),
			),
			array(
				'link' => array(
					'content' => 'entries-search',
					'anchor'  => 'feature-form-entry-management-wordpress',
				),
				'tip'  => __( 'Want to search submitted entries?', 'formidable' ),
				'call' => __( 'Upgrade to Pro.', 'formidable' ),
			),
		);
		$tips = array_merge( $tips, self::get_import_tip() );
		return $tips;
	}

	public static function get_import_tip() {
		$tips = array(
			array(
				'link' => array(
					'content' => 'import',
					'anchor'  => 'feature-importing-exporting-wordpress-forms',
				),
				'tip'  => __( 'Want to import entries into your forms?', 'formidable' ),
				'call' => __( 'Upgrade to Pro.', 'formidable' ),
			),
		);
		return $tips;
	}

	public static function get_banner_tip() {
		$tips = array(
			array(
				'link' => array(
					'medium'  => 'banner',
					'content' => 'professional-results',
				),
				'tip'  => __( 'Looking for more ways to get professional results?', 'formidable' ),
				'call' => __( 'Take your forms to the next level.', 'formidable' ),
			),
			array(
				'link' => array(
					'medium'  => 'banner',
					'content' => 'increase-conversions',
				),
				'tip'  => __( 'Increase conversions in long forms.', 'formidable' ),
				'call' => __( 'Add conditional logic, page breaks, and section headings.', 'formidable' ),
			),
			array(
				'link' => array(
					'medium'  => 'banner',
					'content' => 'automate',
				),
				'tip'  => __( 'Automate your business and increase revenue.', 'formidable' ),
				'call' => __( 'Collect instant payments, and send leads to MailChimp.', 'formidable' ),
			),
		);
		$random = rand( 0, count( $tips ) - 1 );
		$tip = $tips[ $random ];
		$tip['num'] = $random;
		return $tip;
	}

	public static function get_random_tip( $tips ) {
		$random = rand( 0, count( $tips ) - 1 );
		return $tips[ $random ];
	}
}
