<?php
namespace Craft;

class AutoAssignUserGroupPlugin extends BasePlugin
{

	public function init() {
		parent::init();
		craft()->on('users.saveUser', function(Event $event) {
			// Set target group
			$targetGroups = craft()->plugins->getPlugin('autoAssignUserGroup')->getSettings()->userGroups;
			// Ensure all target groups exist
			$this->_ensureGroupsExist($targetGroups);
			// If target groups exist
			if ($targetGroups) {
				// If new user
				if ($event->params['isNewUser']) {
					// If "groups" is in POST
					if (isset($_POST['groups'])) {
						// If not an array, it's an empty string
						if (!is_array($_POST['groups'])) {
							// Set it to an array with the user group ID
							$_POST['groups'] = $targetGroups;
						} else {
							// Merge in the user group ID with any existing ones.
							$_POST['groups'] = array_merge($_POST['groups'], $targetGroups);
						}
					}
				}
			}
		});
	}

	public function getName()
	{
		return 'Auto-Assign User Group';
	}

	public function getDescription()
	{
		return 'Automatically assign new users to a specific user group.';
	}

	public function getDocumentationUrl()
	{
		return 'https://github.com/lindseydiloreto/craft-autoassignusergroup';
	}

	public function getVersion()
	{
		return '1.1.0';
	}

	public function getSchemaVersion()
	{
		return '1.1.0';
	}

	public function getDeveloper()
	{
		return 'Double Secret Agency';
	}

	public function getDeveloperUrl()
	{
		return 'https://github.com/lindseydiloreto/craft-autoassignusergroup';
		//return 'http://doublesecretagency.com';
	}

	protected function defineSettings()
	{
		return array(
			'userGroups' => array(AttributeType::Mixed),
		);
	}

	public function getSettingsHtml()
	{
		// If Craft Pro
		if (craft()->getEdition() == Craft::Pro) {

			$options = array();
			$userGroups = craft()->userGroups->getAllGroups();
			foreach ($userGroups as $group) {
				$options[] = array(
					'label' => $group->name,
					'value' => $group->id,
				);
			}

			$checkboxes = craft()->templates->render('_includes/forms/checkboxGroup', array(
				'name'    => 'userGroups',
				'options' => $options,
				'values'  => $this->getSettings()->userGroups,
			));

			$noGroups = '<p class="error">No user groups exist. <a href="'.UrlHelper::getCpUrl('settings/users/groups/new').'">Create one now...</a></p>';

	        craft()->templates->includeCssResource('autoassignusergroup/css/settings.css');

			return craft()->templates->render('autoassignusergroup/_settings', array(
				'userGroupsField' => TemplateHelper::getRaw(count($userGroups) ? $checkboxes : $noGroups),
			));

		} else {

			craft()->templates->includeJs('$(".btn.submit").val("Continue");');
			$output  = '<h2>Craft Upgrade Required</h2>';
			$output .= '<p>In order to use this plugin, Craft Pro is required.</p>';
			return craft()->templates->renderString($output);

		}
	}

	private function _ensureGroupsExist(&$targetGroups)
	{
		// Stop everything if target groups don't exist
		if (!$targetGroups || !is_array($targetGroups)) {
			$targetGroups = false;
			return;
		}
		// Compile IDs of existing groups
		$existingIds = array();
		foreach (craft()->userGroups->getAllGroups() as $group) {
			$existingIds[] = $group->id;
		}
		// Loop through target group IDs
		foreach ($targetGroups as $targetGroupId) {
			// If target group doesn't exist
			if (!in_array($targetGroupId, $existingIds)) {
				// Throw error message
				$pluginName = $this->getName();
				$errorMessage = "The default user group no longer exists. Please update your settings for the {$pluginName} plugin.";
				throw new Exception($errorMessage);
			}
		}
	}

}