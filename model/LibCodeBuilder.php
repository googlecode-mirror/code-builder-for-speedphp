<?php
/**
 * @author PonyCui
 */
class LibCodeBuilder {
	/**
	 * 供smarty注册使用的函数
	 */
	public function cbFormWidget($params){
		extract($params);
		extract($moreParams);
		switch(strtolower($type)){
			case 'dropdownlist':
				return $this ->_cbFormDropDownList($name, $options, $value, $htmlOptions);
			case 'textfield':
				return $this ->_cbFormTextField($name, $value, $htmlOptions);
			case 'password':
				return $this ->_cbFormPassword($name, $value, $htmlOptions);
			case 'textarea':
				return $this ->_cbFormTextArea($name, $value, $htmlOptions);
			case 'checkbox':
				return $this ->_cbFormCheckBox($name, $checkBoxText, $checkBoxValue, $value, $htmlOptions);
			case 'radio':
				return $this ->_cbFormRadio($name, $value, $options);
			case 'file':
				return $this ->_cbFormFile($name, $htmlOptions);
			case 'default':
				return $this ->_cbFormTextField($name, $value, $htmlOptions);
		}
	}
	
	private function _cbFormDropDownList($name, $options = array(), $value = 0 ,$htmlOptions = array()){
		$html = '<select name="'.$name.'" ';
		foreach($htmlOptions as $htmlKey=>$htmlValue){
			$html .= $htmlKey.'="'.$htmlValue.'" ';
		}
		$html.= '>';
		foreach($options as $optionValue => $optionText){
			if($value == $optionValue)
				$html .= '<option value="'.$optionValue.'" selected>'.$optionText.'</option>';
			else
				$html .= '<option value="'.$optionValue.'">'.$optionText.'</option>';
		}
		$html .= '</select>';
		return $html;
	}
	
	private function _cbFormTextField($name, $value = '', $htmlOptions = array()){
		$html = '<input type="text" name="'.$name.'" value="'.$value.'" ';
		foreach($htmlOptions as $htmlKey=>$htmlValue){
			$html .= $htmlKey.'="'.$htmlValue.'" ';
		}
		$html.= ' />';
		return $html;
	}
	
	private function _cbFormPassword($name, $value = '', $htmlOptions = array()){
		$html = '<input type="password" name="'.$name.'" value="'.$value.'" ';
		foreach($htmlOptions as $htmlKey=>$htmlValue){
			$html .= $htmlKey.'="'.$htmlValue.'" ';
		}
		$html.= ' />';
		return $html;
	}
	
	private function _cbFormTextArea($name, $value = '', $htmlOptions = array()){
		$html = '<textarea name="'.$name.'" ';
		foreach($htmlOptions as $htmlKey=>$htmlValue){
			$html .= $htmlKey.'="'.$htmlValue.'" ';
		}
		$html.= '>'.htmlspecialchars($value).'</textarea>';
		return $html;
	}
	
	private function _cbFormCheckBox($name, $checkBoxText = '', $checkBoxValue = '', $value = '', $htmlOptions = array()){
		$html = '<label class="checkbox"><input type="checkbox" name="'.$name.'" value="'.$checkBoxValue.'" ';
		if($checkBoxValue == $value)
			$html.= 'checked="checked" ';
		foreach($htmlOptions as $htmlKey=>$htmlValue){
			$html .= $htmlKey.'="'.$htmlValue.'" ';
		}
		$html .= '/>'.$checkBoxText.'</label>';
		return $html;
	}
	
	private function _cbFormRadio($name, $value = '', $options = array()){
		$html = '';
		foreach($options as $optionValue => $optionText){
			if($value == $optionValue)
				$html .= '<label class="radio inline"><input type="radio" name="'.$name.'" value="'.$optionValue.'" checked>'.$optionText.'</label>';
			else
				$html .= '<label class="radio inline"><input type="radio" name="'.$name.'" value="'.$optionValue.'">'.$optionText.'</label>';
		}
		return $html;
	}
	
	private function _cbFormFile($name, $htmlOptions = array()){
		$html = '<input type="file" name="'.$name.'" ';
		foreach($htmlOptions as $htmlKey=>$htmlValue){
			$html .= $htmlKey.'="'.$htmlValue.'" ';
		}
		$html.= ' />';
		return $html;
	}
}

?>
