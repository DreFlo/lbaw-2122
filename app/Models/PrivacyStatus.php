<?php

namespace App\Models;

class PrivacyStatus extends BasicEnum {
    const PublicStatus = 'Public';
    const PrivateStatus = 'Private';
    const AnonymousStatus = 'Anonymous';
    const BannedStatus = 'Banned';
}