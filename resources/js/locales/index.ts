import frHome from './fr/home';
import frPrivacyPolicy from './fr/privacyPolicy';
import frTermsOfService from './fr/termsOfService';
import frFriend from './fr/friend';
import frTrip from './fr/trip';

import enHome from './en/home'
import enPrivacyPolicy from './en/privacyPolicy';
import enTermsOfService from './en/termsOfService';
import enFriend from './en/friend';
import enTrip from './en/trip';

export default {
    fr: {
        ...frHome,
        ...frPrivacyPolicy,
        ...frTermsOfService,
        ...frFriend,
        ...frTrip
    },
    en: {
        ...enHome,
        ...enPrivacyPolicy,
        ...enTermsOfService,
        ...enFriend,
        ...enTrip
    }
}
