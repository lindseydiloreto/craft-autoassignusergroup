<?php
namespace Craft;

class AutoAssignUserGroupPlugin extends BasePlugin
{

	public function init() {
		parent::init();
		craft()->on('users.onSaveUser', function(Event $event) {

			// if we are creating a new user
			if ($event->params['isNewUser']) {

				// get the user that was just saved
				$user = $event->params['user'];

				// get our target groups from the settings
				$targetGroups = craft()->plugins->getPlugin('autoAssignUserGroup')->getSettings()->userGroups;

				// assign the user to the target group
				craft()->userGroups->assignUserToGroups($user->id, $targetGroups);
			}
		});
	}

	public function getName()
	{
		return 'Auto-Assign User Group';
	}

	public function getVersion()
	{
		return '1.0.0';
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
	}
	
}
