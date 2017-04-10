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
namespace ExtraMetabox;
class Html extends AbstractField {

	protected $template;
	protected $content;

	public function the_admin() {
		?>
		<div class="<?php echo $this->css_class; ?> extra-html-container">
			<?php
			if ($this->template !== null) {
				include $this->template;
			}
			if ($this->content !== null) {
				echo $this->content;
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
		$this->content = isset($properties['content']) ? $properties['content'] : null;
		$this->template_admin_column = isset($properties['template_admin_column']) ? $properties['template_admin_column'] : null;
	}

	public function the_admin_column_value() {
		if ($this->template_admin_column !== null) {
			include $this->template_admin_column;
		}
	}
}