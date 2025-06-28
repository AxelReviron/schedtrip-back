import frHome from './fr/home';
import frPrivacyPolicy from './fr/privacyPolicy';
import frTermsOfService from './fr/termsOfService';

import enHome from './en/home'
import enPrivacyPolicy from './en/privacyPolicy';
import enTermsOfService from './en/termsOfService';

export default {
    fr: {
        ...frHome,
        ...frPrivacyPolicy,
        ...frTermsOfService
    },
    en: {
        ...enHome,
        ...enPrivacyPolicy,
        ...enTermsOfService
    }
}
