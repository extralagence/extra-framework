<?php
/**
 * Class Html
 *
 * Define a raw html metabox
 *
 * type = html
 *
 * Options :
 * - template (optionnal)
 */
class Html extends AbstractField {

	protected $template;

	public function the_admin() {
		?>
		<div class="<?php echo $this->css_class; ?> extra-html-container">
			<?php
			if ($this->template !== null) {
				include $this->template;
			}
			?>
		</div>
		<?php
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		if ($this->name == null || empty($this->name)) {
			$this->name = 'html';
		}

		$this->template = isset($properties['template']) ? $properties['template'] : null;
	}
}