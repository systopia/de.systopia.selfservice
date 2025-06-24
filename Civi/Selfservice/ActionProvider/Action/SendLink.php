<?php
/*------------------------------------------------------------+
| Selfservice extension                                       |
| Copyright (C) 2019 SYSTOPIA                                 |
| Author: B. Endres (endres@systopia.de)                      |
+-------------------------------------------------------------+
| This program is released as free software under the         |
| Affero GPL license. You can redistribute it and/or          |
| modify it under the terms of this license which you         |
| can read by viewing the included agpl.txt or online         |
| at www.gnu.org/licenses/agpl.html. Removal of this          |
| copyright header is strictly prohibited without             |
| written permission from the original author(s).             |
+-------------------------------------------------------------*/

namespace Civi\Selfservice\ActionProvider\Action;

use CRM_Selfservice_ExtensionUtil as E;

use \Civi\ActionProvider\Action\AbstractAction;
use \Civi\ActionProvider\Parameter\ParameterBagInterface;
use \Civi\ActionProvider\Parameter\Specification;
use \Civi\ActionProvider\Parameter\SpecificationBag;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SendLink extends AbstractAction implements CompilerPassInterface {

  /**
   * Register this one action: SelfServiceResolve
   */
  public function process(ContainerBuilder $container) {
    if (!$container->hasDefinition('action_provider')) {
      return;
    }
    $typeFactoryDefinition = $container->getDefinition('action_provider');
    $typeFactoryDefinition->addMethodCall(
      'addAction',
      [
        'Sendlink',
        \Civi\Selfservice\ActionProvider\Action\SendLink::class,
        E::ts('Send Self-Service Token to Email-Adress'),
        [
          AbstractAction::SINGLE_CONTACT_ACTION_TAG,
          AbstractAction::DATA_RETRIEVAL_TAG,
        ],
      ]
    );
  }

  /**
   * Returns the specification of the configuration options for the actual action.
   *
   * @return SpecificationBag specs
   */
  public function getConfigurationSpecification() {
    return new SpecificationBag(
      [
        new Specification(
          'default_profile',
          'String',
          E::ts('Default Profile'),
          FALSE,
          NULL,
          NULL,
          $this->getProfiles(),
          FALSE
        ),
      ]
    );
  }

  protected function getProfiles() {
    $profiles = [];
    foreach (\CRM_Selfservice_SendLinkProfile::getProfiles() as $profile_name => $profile) {
      $profiles[$profile_name] = $profile_name;
    }
    return $profiles;
  }

  /**
   * Returns the specification of the parameters of the actual action.
   *
   * @return SpecificationBag specs
   */
  public function getParameterSpecification() {
    return new SpecificationBag(
      [
        new Specification(
          'email',
          'String',
          E::ts('Email'),
          FALSE,
          NULL,
          NULL,
          NULL,
          FALSE
        ),
        new Specification(
          'profile',
          'String',
          E::ts('Profile'),
          FALSE,
          NULL,
          NULL,
          NULL,
          FALSE
        ),
      ]
    );
  }

  /**
   * Returns the specification of the output parameters of this action.
   *
   * This function could be overriden by child classes.
   *
   * @return SpecificationBag specs
   */
  public function getOutputSpecification() {
    return new SpecificationBag(
      [
        new Specification(
          'is_error',
          'Integer',
          E::ts('Is Error'),
          FALSE,
          NULL,
          NULL,
          NULL,
          FALSE
        ),
        new Specification(
          'error_message',
          'String',
          E::ts('Error Message'),
          FALSE,
          NULL,
          NULL,
          NULL,
          FALSE
        ),
        new Specification(
          'message',
          'String',
          E::ts('Message'),
          FALSE,
          NULL,
          NULL,
          NULL,
          FALSE
        ),
      ]
    );
  }

  /**
   * Run the action
   *
   * @param ParameterBagInterface $parameters
   *   The parameters to this action.
   * @param ParameterBagInterface $output
   *   The parameters this action can send back
   *
   * @return void
   */
  protected function doAction(ParameterBagInterface $parameters, ParameterBagInterface $output) {
    $params = $parameters->toArray();
    $params['check_permissions'] = 0;

    $profile = $parameters->getParameter('profile');
    if (empty($profile)) {
      $profile = $this->getConfiguration()->getParameter('default_profile');
    }
    if (!empty($profile)) {
      $params['profile'] = $profile;
    }

    // execute
    try {
      $result = \civicrm_api3('Selfservice', 'sendlink', $params);
      $output->setParameter('is_error', $result['is_error']);
      $output->setParameter('error_message', $result['error_message']);
      $output->setParameter('message', $result['values']);
    }
    catch (\Exception $ex) {
      $output->setParameter('is_error', 1);
      $output->setParameter('error_message', $ex->getMessage());
      $output->setParameter('message', '');
    }
  }

}
