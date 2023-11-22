<?php

/*
<COPYRIGHT>

Copyright © 2022-2023, Canyon GBS LLC

All rights reserved.

This file is part of a project developed using Laravel, which is an open-source framework for PHP.
Canyon GBS LLC acknowledges and respects the copyright of Laravel and other open-source
projects used in the development of this solution.

This project is licensed under the Affero General Public License (AGPL) 3.0.
For more details, see https://github.com/canyongbs/assistbycanyongbs/blob/main/LICENSE.

Notice:
- The copyright notice in this file and across all files and applications in this
 repository cannot be removed or altered without violating the terms of the AGPL 3.0 License.
- The software solution, including services, infrastructure, and code, is offered as a
 Software as a Service (SaaS) by Canyon GBS LLC.
- Use of this software implies agreement to the license terms and conditions as stated
 in the AGPL 3.0 License.

For more information or inquiries please visit our website at
https://www.canyongbs.com or contact us via email at legal@canyongbs.com.

</COPYRIGHT>
*/

namespace Assist\Campaign\Policies;

use App\Models\User;
use Assist\Campaign\Models\Campaign;
use Illuminate\Auth\Access\Response;

class CampaignPolicy
{
    public function viewAny(User $user): Response
    {
        return $user->canOrElse(
            abilities: 'campaign.view-any',
            denyResponse: 'You do not have permission to view campaigns.'
        );
    }

    public function view(User $user, Campaign $campaign): Response
    {
        return $user->canOrElse(
            abilities: ['campaign.*.view', "campaign.{$campaign->id}.view"],
            denyResponse: 'You do not have permission to view this campaign.'
        );
    }

    public function create(User $user): Response
    {
        return $user->canOrElse(
            abilities: 'campaign.create',
            denyResponse: 'You do not have permission to create campaigns.'
        );
    }

    public function update(User $user, Campaign $campaign): Response
    {
        return $user->canOrElse(
            abilities: ['campaign.*.update', "campaign.{$campaign->id}.update"],
            denyResponse: 'You do not have permission to update this campaign.'
        );
    }

    public function delete(User $user, Campaign $campaign): Response
    {
        return $user->canOrElse(
            abilities: ['campaign.*.delete', "campaign.{$campaign->id}.delete"],
            denyResponse: 'You do not have permission to delete this campaign.'
        );
    }

    public function restore(User $user, Campaign $campaign): Response
    {
        return $user->canOrElse(
            abilities: ['campaign.*.restore', "campaign.{$campaign->id}.restore"],
            denyResponse: 'You do not have permission to restore this campaign.'
        );
    }

    public function forceDelete(User $user, Campaign $campaign): Response
    {
        return $user->canOrElse(
            abilities: ['campaign.*.force-delete', "campaign.{$campaign->id}.force-delete"],
            denyResponse: 'You do not have permission to permanently delete this campaign.'
        );
    }
}