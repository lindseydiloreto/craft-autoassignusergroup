<?php
namespace Craft;

class AutoAssignUserGroupPlugin extends BasePlugin
{

    public function init() {
        parent::init();
        craft()->on('users.saveUser', function(Event $event) {
            // Set target group
            $targetGroups = craft()->plugins->getPlugin('autoAssignUserGroup')->getSettings()->userGroups;
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
        });
    }

    public function getName()
    {
        return 'Auto-Assign User Group';
    }

    public function getVersion()
    {
        return '0.9.0';
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

        return craft()->templates->render('autoassignusergroup/_settings', array(
            //'settings' => ,
            'userGroupsField' => TemplateHelper::getRaw(count($userGroups) ? $checkboxes : $noGroups),
        ));
    }
    
}