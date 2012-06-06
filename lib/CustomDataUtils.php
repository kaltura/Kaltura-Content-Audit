<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CustomDataUtils
 *
 * @author Leon
 */
class CustomDataUtils
{
	public static function getCustomDataXSD($client)
	{
		if(is_object($client))
		{
			$customDataProfileId = Config::get('metadataProfileId',false);
			if($customDataProfileId)
			{
				// @TODO: multirequest

				$client = KalturaClientHelper::getClient(KalturaSessionType::ADMIN); // @todo: why admin?

				try
				{
					$customDataProfile = $client->metadataProfile->get($customDataProfileId);
				}
				catch(KalturaException $ex)
				{
					if ($ex->getCode() == 'INVALID_OBJECT_ID')
					{
						// @todo: this should really be loogged and not written to page
						return '<div style="clear:both" class="kerror"><b style="color:red">ERROR</b>: customDataProfileId ' . $customDataProfileId . ' does not exist.&nbsp; Check your MediaSpace Config and/ or KMC > Settings > Custom Data.</div>';
					}
					else
					{
						throw $ex;
					}			
				}

				return $customDataProfile->xsd;
			}	
		}
	}
}

?>
