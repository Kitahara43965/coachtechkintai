import { DraftTimetableModalTable } from "./draft-timetable-modal-table.js";
import { STRING_POST_TYPE } from "../constants/constants.js";

export class DraftTimetableModal {
    static showDraftTimetableModal(
        stringPostType,
        hasErrorMessageChange,
        fetchedStatus,
        serverErrorStatus,
        hasTimeParseErrorStatusNoTimeFormatMessage,
        draftTimetableDTO,
        overlappedTimetables,
        overlappedDraftTimetables,
        stringTimeZone,
        dateNow,
        config
    ) {
        let draftTimetableModal = null;
        let draftTimetableSubmitButtonId = null;
        let draftTimetableModalTableId = null;
        let draftTimetableModalId = null;
        let draftTimetableModalMessageId = null;
        let draftTimetableCloseButtonId = null;
        let draftTimetableSubmitButton = null;
        let draftTimetableModalTable = null;
        let draftTimetableCloseButton = null;
        let undefinedServerErrorStatus = 0;
        let noServerErrorStatus = 0;
        let noDataServerErrorStatus = 0;
        let validationErrorServerErrorStatus = 0;
        let stringHtmlDraftTimetableList = null;
        let draftTimetableCloseButtonMessage = "戻る";
        let isDraftModal = false;
        let stringDraftTimetableModalMessage = null;
        let stringFormattedDraftTimetableModalMessage = null;
        let draftTimetableModalMessage = null;

        if (config) {
            draftTimetableSubmitButtonId = config.draftTimetableSubmitButtonId;
            draftTimetableModalTableId = config.draftTimetableModalTableId;
            draftTimetableCloseButtonId = config.draftTimetableCloseButtonId;
            undefinedServerErrorStatus = config.undefinedServerErrorStatus;
            noServerErrorStatus = config.noServerErrorStatus;
            noDataServerErrorStatus = config.noDataServerErrorStatus;
            validationErrorServerErrorStatus = config.validationErrorServerErrorStatus;
            draftTimetableModalId = config.draftTimetableModalId;
            draftTimetableModalMessageId = config.draftTimetableModalMessageId;
        } //config

        if (serverErrorStatus) {
            if (serverErrorStatus === noServerErrorStatus) {
                if (fetchedStatus) {
                    if (fetchedStatus === "success") {
                        if (hasErrorMessageChange === true) {
                            isDraftModal = true;
                            stringDraftTimetableModalMessage = "勤務日が同じ日付の以下の勤務時間が削除されます(修正申請中のものは除く)。";
                        } //stringPostType
                    }
                } //fetchedStatus
            } else if (serverErrorStatus === noDataServerErrorStatus) {
            } else if (serverErrorStatus === validationErrorServerErrorStatus) {
                if (hasTimeParseErrorStatusNoTimeFormatMessage === true) {
                    if (hasErrorMessageChange === true) {
                        isDraftModal = true;
                        stringDraftTimetableModalMessage =
                            "入力値が時間ではありません。\n「(年/)月/日 時:分」 または 「時:分」 で入力してください。\n年:1~9999、最大4桁 \n月:1~12、最大2桁 \n日:1~末日(値)、最大2桁\n時:0~23、最大2桁\n分:0~59、最大2桁";
                    } //stringPostType
                    //delete-modal
                } //hasTimeParseErrorStatusNoTimeFormatMessage
            } //serverErrorStatus
        } //serverErrorStatus

        if (draftTimetableModalId) {
            draftTimetableModal = document.getElementById(draftTimetableModalId);
        } //draftTimetableModalId
        if (draftTimetableCloseButtonId) {
            draftTimetableCloseButton = document.getElementById(draftTimetableCloseButtonId);
        } //draftTimetableCloseButtonId
        if (draftTimetableCloseButton) {
            draftTimetableCloseButton.innerHTML = draftTimetableCloseButtonMessage;
        } //draftTimetableCloseButton
        if (draftTimetableModalTableId) {
            draftTimetableModalTable = document.getElementById(draftTimetableModalTableId);
        } //draftTimetableModalTableId

        if (DraftTimetableModalTable) {
            if (typeof DraftTimetableModalTable.getStringHtmlDraftTimetableList === "function") {
                stringHtmlDraftTimetableList = DraftTimetableModalTable.getStringHtmlDraftTimetableList(
                    draftTimetableDTO,
                    overlappedTimetables,
                    overlappedDraftTimetables,
                    stringTimeZone,
                    dateNow
                );
            } //typeof DraftTimetableModalTable.getStringHtmlDraftTimetableList
        } //DraftTimetableModalTable
        if (draftTimetableModalTable) {
            draftTimetableModalTable.innerHTML = stringHtmlDraftTimetableList;
        } //draftTimetableModalTable

        if (draftTimetableSubmitButtonId) {
            draftTimetableSubmitButton = document.getElementById(draftTimetableSubmitButtonId);
        } //draftTimetableSubmitButtonId
        if (draftTimetableSubmitButton) {
            if (serverErrorStatus === noServerErrorStatus) {
                draftTimetableSubmitButton.style.display = "block";
            } else {
                draftTimetableSubmitButton.style.display = "none";
            } //serverErrorStatus
        } //draftTimetableSubmitButton

        if (stringDraftTimetableModalMessage) {
            // 改行を <br> に変換して表示
            stringFormattedDraftTimetableModalMessage = stringDraftTimetableModalMessage.replace(/\n/g, "<br>");
        } //stringDraftTimetableModalMessage

        if (draftTimetableModalMessageId) {
            draftTimetableModalMessage = document.getElementById(draftTimetableModalMessageId);
        } //draftTimetableModalMessageId
        if (draftTimetableModalMessage) {
            if (hasErrorMessageChange === true) {
                draftTimetableModalMessage.innerHTML = stringFormattedDraftTimetableModalMessage;
            } //hasErrorMessageChange
        } //draftTimetableModalMessage

        if (draftTimetableModal) {
            if (isDraftModal === true) {
                draftTimetableModal.style.display = "block";
            } //isDraftModal
            if (stringPostType === STRING_POST_TYPE.DRAFT_TIMETABLE_SUBMIT) {
                draftTimetableModal.style.display = "none";
            }

            if (!draftTimetableModal.dataset.listenerAdded) {
                draftTimetableModal.addEventListener("click", (e) => {
                    // モーダル内部の要素（ボタン、テーブルなど）がクリックされた場合、イベントをキャンセル
                    e.stopPropagation();
                });

                window.addEventListener("click", (e) => {
                    draftTimetableModal.style.display = "none"; // モーダルを非表示にする
                });
                // リスナーが追加されたことを記録
                draftTimetableModal.dataset.listenerAdded = "true";
            }

            // モーダルの閉じるボタンのクリックイベントをリセット
            if (draftTimetableCloseButton) {
                draftTimetableCloseButton.removeEventListener("click", draftTimetableCloseButton);
                draftTimetableCloseButton.addEventListener("click", () => {
                    draftTimetableModal.style.display = "none"; // モーダルを非表示にする
                });
            }
        } //draftTimetableModal
    }
}
