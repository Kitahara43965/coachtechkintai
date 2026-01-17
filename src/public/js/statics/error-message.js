export class ErrorMessage {
    static displayErrorMessages(errors) {
        let fieldMatch = null;
        let selector = null;
        let errorSpan = null;
        let message = null;

        if (errors && typeof errors === "object") {
            Object.entries(errors).forEach(([field, messages]) => {
                if (Array.isArray(messages)) {
                    fieldMatch = field.match(/^(.+)\.(\d+)$/);
                    selector = null;
                    errorSpan = null;
                    message = messages.join(", ");

                    if (fieldMatch) {
                        const [, base, index] = fieldMatch;
                        selector = `.error-message[data-name="${base}"][data-index="${index}"]`;
                    } else {
                        selector = `.error-message[data-name="${field}"]`;
                    }

                    if (selector) {
                        errorSpan = document.querySelector(selector);
                    }

                    if (errorSpan) {
                        errorSpan.textContent = message;
                        errorSpan.style.color = "red";
                    }
                }
            });
        }
    } //displayErrorMessages

    static hasErrorMessage(errors, errorMessage) {
        let hasErrorMessage = false;
        if (errors && typeof errors === "object") {
            hasErrorMessage = Object.values(errors).some((messages) => Array.isArray(messages) && messages.includes(errorMessage));
        } //errors
        return hasErrorMessage;
    } //hasErrorMessage
} //ErrorMessage
