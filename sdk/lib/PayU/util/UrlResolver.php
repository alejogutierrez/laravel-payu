<?php

/**
 *
 * Util class to build the url to api operations
 *
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0, 16/09/2014
 *
 */
abstract class UrlResolver{
	
	/** constant to add operation */
	const ADD_OPERATION = "add";
	
	/** constant to edit operation */
	const EDIT_OPERATION = "edit";
	
	/** constant to delete operation */
	const DELETE_OPERATION = "delete";
	
	/** constant to get operation */
	const GET_OPERATION = "get";
	
	/** constant to query operation */
	const QUERY_OPERATION = "query";
	
	/** constant to get list operation */
	const GET_LIST_OPERATION = "getList";	
	
	/** contains the url info to each entity and operation this is built in the constructor class */
	protected $urlInfo;
	
	/**
	 * build an url segment using the entity, operation and the url params
	 * @param string $entity
	 * @param string $operation
	 * @param string $params
	 * @throws InvalidArgumentException
	 * @return the url segment built
	 */
	public function getUrlSegment($entity, $operation, $params = NULL){
	
		if(!isset($this->urlInfo[$entity])){
			throw new InvalidArgumentException("the entity " . $entity. 'was not found ');
		}
	
		if(!isset($this->urlInfo[$entity][$operation])){
			throw new InvalidArgumentException("the request method " . $requestMethod. 'was not found ');
		}
	
		$numberParams = $this->urlInfo[$entity][$operation]['numberParams'];
	
		if(!isset($params) && $numberParams > 0){
			throw new InvalidArgumentException("the url needs " . $numberParams. ' parameters ');
		}
	
		if(isset($params) && count($params) != $numberParams){
			throw new InvalidArgumentException("the url needs " . $numberParams. ' parameters  but ' . count($params) . 'was received');
		}
	
		if(!is_array($params)){
			$params = array($params);
		}
	
		return vsprintf($this->urlInfo[$entity][$operation]['segmentPattern'],$params);
	
	}	
	
}
