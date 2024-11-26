import { Controller } from '@hotwired/stimulus';
import constants from "../js/constants.js"

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['debug','searchForm']
    static values = {
        something: String,
    }
    connect() {

        console.log('hello from ' + this.identifier + ' ' + this.urlValue);
        console.log(constants.INPUT_DATA_CHANGED); // 'some value'

        this.searchFormTarget.addEventListener('change', (evt) => {
            const formData = new FormData(evt.currentTarget);

            // Convert to an object
            let formObj = this.serialize(formData);
            console.log('dispatching ', formObj);

            const customEvent = new CustomEvent(constants.INPUT_DATA_CHANGED,  {
                detail: {
                    data: formObj,
                    a: 'dispatch event data',
                    searchFormElement: this.searchFormTarget,
                    name: 'cat'
                },
            });
            window.dispatchEvent(customEvent);

        });
    }

    // https://gomakethings.com/how-to-serialize-form-data-with-vanilla-js/#:~:text=To%20serialize%20a%20FormData%20object,it%20into%20a%20query%20string.
    serialize (data) {
        let obj = {};
        for (let [key, value] of data) {
            if (obj[key] !== undefined) {
                if (!Array.isArray(obj[key])) {
                    obj[key] = [obj[key]];
                }
                obj[key].push(value);
            } else {
                obj[key] = value;
            }
        }
        return obj;
    }

    updateCalendar(event) {
        console.log(event);
        this.debugTarget.innerHTML = constants.INPUT_DATA_CHANGED;

        // const customEvent = new CustomEvent(constants.INPUT_DATA_CHANGED);
        // window.dispatchEvent(customEvent, this.searchFormTarget);
    }
}
