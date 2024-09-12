import {Controller} from '@hotwired/stimulus';
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

const routes = require('../../public/js/fos_js_routes.json');
Routing.setRoutingData(routes);

export default class extends Controller {

    submit(e) {
        e.preventDefault();
        let input = 'Java';

        location.href = Routing.generate('search') + "?q=" + input;
    }
}
