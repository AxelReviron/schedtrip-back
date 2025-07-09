import frHome from './fr/home';
import frPrivacyPolicy from './fr/privacyPolicy';
import frTermsOfService from './fr/termsOfService';
import frFriend from './fr/friend';
import frTrip from './fr/trip';
import frNavbar from './fr/navbar';
import frFooter from './fr/footer';
import frDiscover from './fr/discover';
import frAbout from './fr/about';
import frSetting from './fr/setting';

import enHome from './en/home'
import enPrivacyPolicy from './en/privacyPolicy';
import enTermsOfService from './en/termsOfService';
import enFriend from './en/friend';
import enTrip from './en/trip';
import enNavbar from './en/navbar';
import enFooter from './en/footer';
import enDiscover from './en/discover';
import enAbout from './en/about';
import enSetting from './en/setting';


export default {
    fr: {
        ...frHome,
        ...frPrivacyPolicy,
        ...frTermsOfService,
        ...frFriend,
        ...frTrip,
        ...frNavbar,
        ...frFooter,
        ...frDiscover,
        ...frAbout,
        ...frSetting,
    },
    en: {
        ...enHome,
        ...enPrivacyPolicy,
        ...enTermsOfService,
        ...enFriend,
        ...enTrip,
        ...enNavbar,
        ...enFooter,
        ...enDiscover,
        ...enAbout,
        ...enSetting,
    }
}
