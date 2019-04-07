<?php 

namespace Brownbag\Presentation\Api\Data;

use Magento\Framework\Data\SearchResultInterface;

/**
 * @codeCoverageIgnore
 */
interface PresentationSearchResultInterface extends SearchResultInterface {

	/**
	 * Get items list.
	 *
	 * @return \Magento\Framework\Api\ExtensibleDataInterface[]
	 */
	public function getItems();

	/**
	 * Set items list.
	 *
	 * @param \Magento\Framework\Api\ExtensibleDataInterface[] $items
	 * @return $this
	 */
	public function setItems(array $items);

}