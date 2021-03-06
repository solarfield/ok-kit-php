<?php
namespace Solarfield\Ok;

trait EventTargetTrait {
	private $listeners = [];

	protected function addedEventListener(string $aEventType, callable $aListener) {
		if (array_key_exists($aEventType, $this->listeners)) {
			foreach ($this->listeners[$aEventType] as $k => $listener) {
				if ($listener === $aListener) {
					return $k;
				}
			}
		}

		return null;
	}

	protected function hasEventListeners(string $aEventType) {
		return array_key_exists($aEventType, $this->listeners) && count($this->listeners[$aEventType]) > 0;
	}

	protected function dispatchEvent(EventInterface $aEvent, array $aOptions = []) {
		$options = [
			'listener' => null,
		];
		if ($aOptions) $options = array_replace($options, $aOptions);

		$type = $aEvent->getType();

		$listeners =
			$options['listener']
				? [$options['listener']]
				: (array_key_exists($type, $this->listeners) ? $this->listeners[$type] : []);

		foreach ($listeners as $listener) {
			$listener($aEvent);
		}
	}

	public function addEventListener(string $aEventType, callable $aListener) {
		if (!$this->addedEventListener($aEventType, $aListener)) {
			if (!array_key_exists($aEventType, $this->listeners)) {
				$this->listeners[$aEventType] = [];
			}

			$this->listeners[$aEventType][] = $aListener;
		}
	}
}
