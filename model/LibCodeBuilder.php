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
				if(empty($options)) $options = array();
				if(empty($value)) $value = 0;
				if(empty($htmlOptions)) $htmlOptions = array();
				return $this ->_cbFormDropDownList($name, $options, $value, $htmlOptions);
			case 'textfield':
				if(empty($value)) $value = '';
				if(empty($htmlOptions)) $htmlOptions = array();
				return $this ->_cbFormTextField($name, $value, $htmlOptions);
			case 'password':
				if(empty($value)) $value = '';
				if(empty($htmlOptions)) $htmlOptions = array();
				return $this ->_cbFormPassword($name, $value, $htmlOptions);
			case 'textarea':
				if(empty($value)) $value = '';
				if(empty($htmlOptions)) $htmlOptions = array();
				return $this ->_cbFormTextArea($name, $value, $htmlOptions);
			case 'checkbox':
				if(empty($checkBoxText)) $checkBoxText = '';
				if(empty($checkBoxValue)) $checkBoxValue = 'on';
				if(empty($value)) $value = '';
				if(empty($htmlOptions)) $htmlOptions = array();
				return $this ->_cbFormCheckBox($name, $checkBoxText, $checkBoxValue, $value, $htmlOptions);
			case 'radio':
				if(empty($value)) $value = '';
				if(empty($options)) $options = array();
				return $this ->_cbFormRadio($name, $value, $options);
			case 'file':
				if(empty($htmlOptions)) $htmlOptions = array();
				return $this ->_cbFormFile($name, $htmlOptions);
			case 'default':
				if(empty($value)) $value = '';
				if(empty($htmlOptions)) $htmlOptions = array();
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
