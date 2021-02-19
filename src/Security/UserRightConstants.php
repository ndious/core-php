<?php

namespace BackBeeCloud\Security;

use function in_array;

/**
 * Class UserRightConstants
 *
 * @package BackBeeCloud\Security
 *
 * @author Eric Chau <eric.chau@lp-digital.fr>
 */
final class UserRightConstants
{
    public const SUPER_ADMIN_ID = 'super_admin';

    // context mask constants
    public const NO_CONTEXT_MASK = 0;
    public const PAGE_TYPE_CONTEXT_MASK = 1;   // 2**0
    public const CATEGORY_CONTEXT_MASK = 2;    // 2**1

    //
    // attribute constants
    //

    // check identity constants
    public const CHECK_IDENTITY_ATTRIBUTE = 'CHECK_IDENTITY';

    // page constants
    public const CREATE_ATTRIBUTE = 'CREATE';
    public const EDIT_ATTRIBUTE = 'EDIT';
    public const DELETE_ATTRIBUTE = 'DELETE';
    public const PUBLISH_ATTRIBUTE = 'PUBLISH';
    public const MANAGE_ATTRIBUTE = 'MANAGE';


    // content block attributes constants
    public const CREATE_CONTENT_ATTRIBUTE = 'CREATE_CONTENT';
    public const EDIT_CONTENT_ATTRIBUTE = 'EDIT_CONTENT';
    public const DELETE_CONTENT_ATTRIBUTE = 'DELETE_CONTENT';

    public const OFFLINE_PAGE = 'OFFLINE_PAGE';
    public const ONLINE_PAGE = 'ONLINE_PAGE';

    public const SEO_TRACKING_FEATURE = 'SEO_TRACKING_FEATURE';
    public const TAG_FEATURE = 'TAG_FEATURE';
    public const USER_RIGHT_FEATURE = 'USER_RIGHT_FEATURE';
    public const MULTILANG_FEATURE = 'MULTILANG_FEATURE';
    public const CUSTOM_DESIGN_FEATURE = 'CUSTOM_DESIGN_FEATURE';
    public const PRIVACY_POLICY_FEATURE = 'PRIVACY_POLICY_FEATURE';
    public const GLOBAL_CONTENT_FEATURE = 'GLOBAL_CONTENT_FEATURE';
    public const KNOWLEDGE_GRAPH_FEATURE = 'KNOWLEDGE_GRAPH_FEATURE';

    public const BUNDLE_FEATURE_PATTERN = 'BUNDLE_%s_FEATURE';
    public const BUNDLE_FEATURE_REGEX = '/^BUNDLE_[\w]+_FEATURE$/';

    public static function assertSubjectExists($subject)
    {
        if (!\is_string($subject)) {
            throw new \InvalidArgumentException('Provided value must be type of string.');
        }

        if (1 === preg_match(self::BUNDLE_FEATURE_REGEX, $subject)) {
            return true;
        }

        $result = in_array(
            $subject,
            [
                self::SUPER_ADMIN_ID,
                self::SEO_TRACKING_FEATURE,
                self::TAG_FEATURE,
                self::USER_RIGHT_FEATURE,
                self::MULTILANG_FEATURE,
                self::CUSTOM_DESIGN_FEATURE,
                self::PRIVACY_POLICY_FEATURE,
                self::GLOBAL_CONTENT_FEATURE,
                self::KNOWLEDGE_GRAPH_FEATURE,
                self::OFFLINE_PAGE,
                self::ONLINE_PAGE,
            ]
        );

        if (false === $result) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Provided user right subject value (%s) does not exist.',
                    $subject
                )
            );
        }
    }

    public static function assertAttributeExists($attribute)
    {
        $result = in_array(
            $attribute,
            [
                self::CHECK_IDENTITY_ATTRIBUTE,
                self::CREATE_ATTRIBUTE,
                self::EDIT_ATTRIBUTE,
                self::DELETE_ATTRIBUTE,
                self::PUBLISH_ATTRIBUTE,
                self::MANAGE_ATTRIBUTE,
                self::CREATE_CONTENT_ATTRIBUTE,
                self::EDIT_CONTENT_ATTRIBUTE,
                self::DELETE_CONTENT_ATTRIBUTE,
            ],
            true
        );

        if (false === $result) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Provided user right attribute value (%s) does not exist.',
                    $attribute
                )
            );
        }
    }

    public static function assertContextMaskIsValid($contextMask)
    {
        if (self::NO_CONTEXT_MASK === $contextMask) {
            return;
        }

        $allMasks = [
            self::PAGE_TYPE_CONTEXT_MASK,
            self::CATEGORY_CONTEXT_MASK,
        ];
        if (in_array($contextMask, $allMasks, true)) {
            return;
        }

        foreach ($allMasks as $mask) {
            if ($contextMask & $mask) {
                $contextMask -= $mask;
            }

            if (0 === $contextMask) {
                break;
            }
        }

        if ($contextMask) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Provided user right context mask value (%d) is not valid',
                    $contextMask
                )
            );
        }
    }

    public static function normalizeContextData(array $data)
    {
        if (false === $data) {
            return $data;
        }

        ksort($data);
        foreach ($data as &$row) {
            sort($row);
        }

        return $data;
    }

    public static function createBundleSubject($bundleId)
    {
        return strtoupper(
            sprintf(
                self::BUNDLE_FEATURE_PATTERN,
                $bundleId
            )
        );
    }
}
