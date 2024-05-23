<?php

/**
 * @author nlac
 */
class NLSClientScript extends nlac\NLSClientScript
{

	public function renderHead(&$output)
	{
		$this->_putnlscode();

		//merging
		if ($this->mergeJs)
		{
			$asyncFiles = [];
			foreach ($this->scriptFiles as $pos => $files)
			{
				foreach ($files as $src => $scriptFile)
				{
					if (isset($scriptFile["async"]))
					{
						unset($this->scriptFiles[$pos][$src]);
						$asyncFiles[$src] = $scriptFile;
					}
				}
			}
			$this->scriptFiles["async"] = $asyncFiles;
			$this->_mergeJs(self::POS_HEAD);
			$this->_mergeJs(self::POS_BEGIN);
			$this->_mergeJs(self::POS_END);
			$this->_mergeJs(self::POS_LOAD);
			$this->_mergeJs(self::POS_READY);
			$this->_mergeJs("async");
			foreach ($this->scriptFiles["async"] as $src => $scriptFile)
			{
				$this->scriptFiles[self::POS_HEAD][$src] = ["url" => $src, "async" => "async"];
			}
			$this->mergeJs = false;

			unset($this->scriptFiles["async"]);
		}
		if ($this->mergeCss)
		{
			$this->_mergeCss();
		}

		if (Yii::app()->request->isAjaxRequest)
		{
			goto skipPreloadTags;
		}


		foreach ($this->cssFiles as $url => $media)
			$this->registerLinkTag("preload", null, $url, null, ["as" => "style"]);


		$this->preloadScriptTag();
		$linkTags = [];
		foreach ($this->linkTags as $key => $value)
		{
			if ($value["rel"] != "preload")
			{
				continue;
			}

			$index = 3;
			if ((isset($value["as"]) && $value["as"] == "style") && (!isset($value["fetchpriority"]) || $value["fetchpriority"] != "low"))
			{
				$index = 0;
			}
			elseif ((isset($value["fetchpriority"]) && $value["fetchpriority"] == "high"))
			{
				$index = 1;
			}
			elseif (!isset($value["fetchpriority"]) || (isset($value["fetchpriority"]) && $value["fetchpriority"] == "auto"))
			{
				$index = 2;
			}
			$linkTags[$index][$key] = $value;
			unset($this->linkTags[$key]);
		}

		ksort($linkTags);
		$tags = [];
		foreach ($linkTags as $tag)
		{
			$tags = $tags + $tag;
		}
		$this->linkTags = $tags + $this->linkTags;
		skipPreloadTags:
		parent::renderHead($output);
	}

	public function preloadScriptTag()
	{
		if (!$this->enableJavaScript || Yii::app()->request->isAjaxRequest)
		{
			return;
		}
		foreach ($this->scriptFiles as $pos => $files)
		{
			foreach ($files as $scriptFileValueUrl => $scriptFileValue)
			{
				$priority = "low";
				if ($pos == CClientScript::POS_HEAD && !isset($scriptFileValue["async"]))
				{
					$priority = "high";
				}
				if ($pos == CClientScript::POS_BEGIN && !isset($scriptFileValue["async"]))
				{
					$priority = "auto";
				}
				if (isset($scriptFileValue["defer"]))
				{
					continue;
				}

				$this->registerLinkTag("preload", null, $scriptFileValueUrl, null, ["as" => "script", "fetchpriority" => $priority]);
			}
		}
	}

	/**
	 * Renders the specified core javascript library.
	 */
	public function renderCoreScripts()
	{
		if ($this->coreScripts === null)
			return;
		$cssFiles	 = array();
		$jsFiles	 = array();
		$allJSFiles = [];
		foreach ($this->coreScripts as $name => $package)
		{
			$baseUrl = $this->getPackageBaseUrl($name);
			if (!empty($package['js']))
			{
				$position = !isset($package['coreScriptPosition']) ? $this->coreScriptPosition : $package['coreScriptPosition'];

				foreach ($package['js'] as $js)
				{
					if(isset($allJSFiles[$baseUrl . '/' . $js]))
					{
						continue;
					}
					$allJSFiles[$baseUrl . '/' . $js] = $position;
					$jsFiles[$position][$baseUrl . '/' . $js] = $baseUrl . '/' . $js;
				}
			}
			if (!empty($package['css']))
			{
				foreach ($package['css'] as $css)
					$cssFiles[$baseUrl . '/' . $css] = '';
			}
		}
		// merge in place
		if ($cssFiles !== array())
		{
			foreach ($this->cssFiles as $cssFile => $media)
				$cssFiles[$cssFile]	 = $media;
			$this->cssFiles		 = $cssFiles;
		}
		if ($jsFiles !== array())
		{
			foreach ($this->scriptFiles as $pos => $scriptFiles)
			{
				foreach ($scriptFiles as $url => $value)
					$jsFiles[$pos][$url] = $value;
			}
			$this->scriptFiles = $jsFiles;
		}
	}

	
	public function registerScriptFile($url, $position = null, array $htmlOptions = array()) {
		//$url = $this->addAppVersion($url);
		//\Yii::log('URL regged:' . $url, 'info');
		return parent::registerScriptFile($url, $position, $htmlOptions);
	}
	
	public function registerCssFile($url, $media = '') {
		return parent::registerCssFile($url, $media);
	}
}
