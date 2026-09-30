/*
|--------------------------------------------------------------------------
| INSPECTION FILTERS
|--------------------------------------------------------------------------
*/

const inspectionSearch =
    document.getElementById("inspectionSearch");

const inspectionStatusFilter =
    document.getElementById("inspectionStatusFilter");

const inspectionDateFilter =
    document.getElementById("inspectionDateFilter");

const inspectionList =
    document.getElementById("inspectionList");


/*
|--------------------------------------------------------------------------
| FILTER INSPECTIONS
|--------------------------------------------------------------------------
*/

function filterInspections() {

    const searchValue = inspectionSearch
        ? inspectionSearch.value.trim().toLowerCase()
        : "";

    const statusValue = inspectionStatusFilter
        ? inspectionStatusFilter.value.toLowerCase()
        : "all";

    const dateValue = inspectionDateFilter
        ? inspectionDateFilter.value
        : "";

    const rows = inspectionList
        ? inspectionList.querySelectorAll(".inspection-row")
        : [];

    let visibleCount = 0;

    rows.forEach(row => {

        const feCode =
            row.dataset.feCode || "";

        const location =
            row.dataset.location || "";

        const status =
            row.dataset.status || "";

        const date =
            row.dataset.date || "";


        const matchesSearch =
            searchValue === "" ||
            feCode.includes(searchValue) ||
            location.includes(searchValue);


        const matchesStatus =
            statusValue === "all" ||
            status === statusValue;


        const matchesDate =
            dateValue === "" ||
            date === dateValue;


        const shouldShow =
            matchesSearch &&
            matchesStatus &&
            matchesDate;


        row.style.display =
            shouldShow ? "" : "none";


        if (shouldShow) {
            visibleCount++;
        }

    });


    showNoResults(
        visibleCount === 0
    );

}


/*
|--------------------------------------------------------------------------
| NO RESULTS MESSAGE
|--------------------------------------------------------------------------
*/

function showNoResults(show) {

    if (!inspectionList) {
        return;
    }


    let noResultsRow =
        document.getElementById(
            "noInspectionResults"
        );


    if (show) {

        if (!noResultsRow) {

            noResultsRow =
                document.createElement("tr");

            noResultsRow.id =
                "noInspectionResults";

            noResultsRow.innerHTML = `

                <td
                    colspan="5"
                    class="text-center py-5">

                    <div class="text-body-secondary">

                        <i
                            class="bi bi-search fs-1 d-block mb-2">
                        </i>

                        No inspection records match your filters.

                    </div>

                </td>

            `;

            inspectionList.appendChild(
                noResultsRow
            );

        }

        noResultsRow.style.display = "";

    } else {

        if (noResultsRow) {

            noResultsRow.style.display =
                "none";

        }

    }

}


/*
|--------------------------------------------------------------------------
| FILTER EVENTS
|--------------------------------------------------------------------------
*/

if (inspectionSearch) {

    inspectionSearch.addEventListener(
        "input",
        filterInspections
    );

}


if (inspectionStatusFilter) {

    inspectionStatusFilter.addEventListener(
        "change",
        filterInspections
    );

}


if (inspectionDateFilter) {

    inspectionDateFilter.addEventListener(
        "change",
        filterInspections
    );

}


/*
|--------------------------------------------------------------------------
| INITIAL FILTER
|--------------------------------------------------------------------------
*/

filterInspections();



/*
|--------------------------------------------------------------------------
| VIEW INSPECTION
|--------------------------------------------------------------------------
*/

async function viewInspection(inspectId) {

    try {

        const response = await fetch(
            `backend/controller/InspectionController.php?action=get&id=${inspectId}`
        );


        const data =
            await response.json();


        if (!data.success || !data.inspection) {

            alert(
                "Unable to load inspection details."
            );

            return;

        }


        const inspection =
            data.inspection;


        /*
        |--------------------------------------------------------------------------
        | INSPECTION IDS
        |--------------------------------------------------------------------------
        */

        const approveInspectId =
            document.getElementById(
                "approveInspectId"
            );


        const rejectInspectId =
            document.getElementById(
                "rejectInspectId"
            );


        if (approveInspectId) {

            approveInspectId.value =
                inspectId;

        }


        if (rejectInspectId) {

            rejectInspectId.value =
                inspectId;

        }


        /*
        |--------------------------------------------------------------------------
        | BASIC INFORMATION
        |--------------------------------------------------------------------------
        */

        document.getElementById(
            "viewExtinguisherCode"
        ).value =
            inspection.extinguisher_code || "—";


        document.getElementById(
            "viewInspectionLocation"
        ).value =
            inspection.location || "—";


        document.getElementById(
            "viewInspectionCapacity"
        ).value =
            inspection.capacity || "—";


        document.getElementById(
            "viewInspectionType"
        ).value =
            inspection.type || "—";


        document.getElementById(
            "viewInspectionClass"
        ).value =
            inspection.class || "—";


        document.getElementById(
            "viewInspectedBy"
        ).value =
            inspection.inspected_by || "—";


        /*
        |--------------------------------------------------------------------------
        | REMARKS
        |--------------------------------------------------------------------------
        */

        document.getElementById(
            "viewRemarks"
        ).value =
            inspection.remarks || "N/A";


        /*
        |--------------------------------------------------------------------------
        | DATE INSPECTED
        |--------------------------------------------------------------------------
        */

        if (inspection.date_inspected) {

            const date =
                new Date(
                    inspection.date_inspected
                );


            if (!isNaN(date.getTime())) {

                document.getElementById(
                    "viewDateInspected"
                ).value =
                    date.toLocaleDateString(
                        "en-US",
                        {
                            year: "numeric",
                            month: "long",
                            day: "numeric"
                        }
                    );

            } else {

                document.getElementById(
                    "viewDateInspected"
                ).value =
                    inspection.date_inspected;

            }

        } else {

            document.getElementById(
                "viewDateInspected"
            ).value =
                "—";

        }


        /*
        |--------------------------------------------------------------------------
        | EVALUATION STATUS
        |--------------------------------------------------------------------------
        */

        const status =
            inspection.evaluation_status ||
            "Pending";


        const statusContainer =
            document.getElementById(
                "viewEvaluationStatus"
            );


        let statusClass =
            "bg-warning-subtle text-warning-emphasis";

        let statusIcon =
            "bi-clock";

        let statusLabel =
            "Pending Approval";


        if (status === "Approved") {

            statusClass =
                "bg-success-subtle text-success";

            statusIcon =
                "bi-check-circle-fill";

            statusLabel =
                "Approved";

        } else if (status === "Rejected") {

            statusClass =
                "bg-danger-subtle text-danger";

            statusIcon =
                "bi-x-circle-fill";

            statusLabel =
                "Rejected";

        }


        statusContainer.innerHTML = `

            <span
                class="badge rounded-pill ${statusClass} px-3 py-2">

                <i
                    class="bi ${statusIcon} me-1">
                </i>

                ${statusLabel}

            </span>

        `;


        /*
        |--------------------------------------------------------------------------
        | CORRECTIVE ACTION
        |--------------------------------------------------------------------------
        */

        const actionTaken =
            document.getElementById(
                "viewActionTaken"
            );


        const targetDate =
            document.getElementById(
                "viewTargetDate"
            );


        if (actionTaken) {

            const savedAction =
                inspection.action_taken || "";


            const otherAction =
                actionTaken.parentElement.querySelector(
                    'input[name="other_action"]'
                );


            /*
            |--------------------------------------------------------------------------
            | ALWAYS RESET FIRST
            |--------------------------------------------------------------------------
            */

            actionTaken.value = "";


            if (otherAction) {

                otherAction.value = "";

                otherAction.classList.add(
                    "d-none"
                );

                otherAction.required =
                    false;

            }


            /*
            |--------------------------------------------------------------------------
            | ONLY LOAD SAVED ACTION
            | IF NOT PENDING
            |--------------------------------------------------------------------------
            */

            if (
                status !== "Pending" &&
                savedAction !== ""
            ) {

                const standardActions = [

                    "Refill",
                    "Replacement of Parts",
                    "Replacement of Unit"

                ];


                if (
                    standardActions.includes(
                        savedAction
                    )
                ) {

                    actionTaken.value =
                        savedAction;

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | CUSTOM ACTION
                    |--------------------------------------------------------------------------
                    */

                    actionTaken.value =
                        "Others";


                    if (otherAction) {

                        otherAction.value =
                            savedAction;

                        otherAction.classList.remove(
                            "d-none"
                        );

                        otherAction.required =
                            true;

                    }

                }

            }

        }


        /*
        |--------------------------------------------------------------------------
        | TARGET DATE
        |--------------------------------------------------------------------------
        */

        if (targetDate) {

            targetDate.value =
                inspection.target_date_of_implementation ||
                "";

        }


        /*
        |--------------------------------------------------------------------------
        | CHECKLIST STATUS
        |--------------------------------------------------------------------------
        */

        updateChecklistStatus(
            "Seal",
            inspection.is_seal_ok
        );


        updateChecklistStatus(
            "Pin",
            inspection.is_pin_ok
        );


        updateChecklistStatus(
            "Pressure",
            inspection.is_pressure_ok
        );


        updateChecklistStatus(
            "Hose",
            inspection.is_hose_ok
        );


        updateChecklistStatus(
            "Nozzle",
            inspection.is_nozzle_ok
        );


        updateChecklistStatus(
            "Belt",
            inspection.is_belt_ok
        );


        updateChecklistStatus(
            "CylinderBody",
            inspection.is_cylinder_body_ok
        );


        updateChecklistStatus(
            "DemarcationLine",
            inspection.is_demarcation_line_ok
        );


        updateChecklistStatus(
            "Signage",
            inspection.is_signage_ok
        );


        /*
        |--------------------------------------------------------------------------
        | OVERALL CONDITION
        |--------------------------------------------------------------------------
        */

        const checklistFields = [

            inspection.is_seal_ok,
            inspection.is_pin_ok,
            inspection.is_pressure_ok,
            inspection.is_hose_ok,
            inspection.is_nozzle_ok,
            inspection.is_belt_ok,
            inspection.is_cylinder_body_ok,
            inspection.is_demarcation_line_ok,
            inspection.is_signage_ok

        ];


        const allGood =
            checklistFields.every(
                value =>
                    value == 1 ||
                    value === true ||
                    value === "1"
            );


        const conditionStatus =
            document.getElementById(
                "viewInspectionConditionStatus"
            );


        if (conditionStatus) {

            if (allGood) {

                conditionStatus.className =
                    "badge bg-success ms-auto";

                conditionStatus.textContent =
                    "Good";

            } else {

                conditionStatus.className =
                    "badge bg-danger ms-auto";

                conditionStatus.textContent =
                    "Not Good";

            }

        }


        /*
        |--------------------------------------------------------------------------
        | SHOW MODAL
        |--------------------------------------------------------------------------
        */

        const modalElement =
            document.getElementById(
                "viewInspectionModal"
            );


        const modal =
            bootstrap.Modal.getOrCreateInstance(
                modalElement
            );


        modal.show();


    } catch (error) {

        console.error(
            "Error loading inspection:",
            error
        );


        alert(
            "An error occurred while loading the inspection."
        );

    }

}



/*
|--------------------------------------------------------------------------
| UPDATE CHECKLIST ITEM
|--------------------------------------------------------------------------
*/

function updateChecklistStatus(
    itemName,
    value
) {

    const textElement =
        document.getElementById(
            `view${itemName}Text`
        );


    const statusElement =
        document.getElementById(
            `view${itemName}Status`
        );


    if (!textElement || !statusElement) {
        return;
    }


    const isGood =
        value == 1 ||
        value === true ||
        value === "1";


    if (isGood) {

        textElement.textContent =
            "Good";

        textElement.className =
            "text-success fw-semibold";


        statusElement.className =
            "fs-4 text-success";

        statusElement.innerHTML =
            '<i class="bi bi-check-circle-fill"></i>';

    } else {

        textElement.textContent =
            "Not Good";

        textElement.className =
            "text-danger fw-semibold";


        statusElement.className =
            "fs-4 text-danger";

        statusElement.innerHTML =
            '<i class="bi bi-x-circle-fill"></i>';

    }

}



/*
|--------------------------------------------------------------------------
| CORRECTIVE ACTION - OTHERS
|--------------------------------------------------------------------------
*/

document.addEventListener(
    "DOMContentLoaded",
    function () {

        const actionTaken =
            document.getElementById(
                "viewActionTaken"
            );


        if (!actionTaken) {
            return;
        }


        actionTaken.addEventListener(
            "change",
            function () {

                const otherAction =
                    this.parentElement.querySelector(
                        'input[name="other_action"]'
                    );


                if (!otherAction) {
                    return;
                }


                if (this.value === "Others") {

                    otherAction.classList.remove(
                        "d-none"
                    );

                    otherAction.required =
                        true;

                } else {

                    otherAction.classList.add(
                        "d-none"
                    );

                    otherAction.required =
                        false;

                    otherAction.value =
                        "";

                }

            }
        );

    }
);



/*
|--------------------------------------------------------------------------
| SET CORRECTIVE ACTION VALUES
|--------------------------------------------------------------------------
*/

function setCorrectiveActionValues(
    type
) {

    const actionSelect =
        document.getElementById(
            "viewActionTaken"
        );


    const targetDate =
        document.getElementById(
            "viewTargetDate"
        );


    if (!actionSelect || !targetDate) {

        alert(
            "Corrective Action fields are missing."
        );

        return false;

    }


    const otherAction =
        actionSelect.parentElement.querySelector(
            'input[name="other_action"]'
        );


    let actionValue =
        actionSelect.value;


    let customAction =
        "";


    /*
    |--------------------------------------------------------------------------
    | VALIDATE ACTION
    |--------------------------------------------------------------------------
    */

    if (actionValue === "") {

        alert(
            "Please select an action."
        );


        actionSelect.focus();


        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | OTHERS
    |--------------------------------------------------------------------------
    */

    if (actionValue === "Others") {

        customAction =
            otherAction
                ? otherAction.value.trim()
                : "";


        if (customAction === "") {

            alert(
                "Please specify the other action."
            );


            if (otherAction) {

                otherAction.focus();

            }


            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | SAVE CUSTOM ACTION AS ACTION TAKEN
        |--------------------------------------------------------------------------
        */

        actionValue =
            customAction;

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE TARGET DATE
    |--------------------------------------------------------------------------
    */

    if (targetDate.value === "") {

        alert(
            "Please select the target date of implementation."
        );


        targetDate.focus();


        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    if (type === "approve") {

        const approveAction =
            document.getElementById(
                "approveActionTaken"
            );


        const approveOther =
            document.getElementById(
                "approveOtherAction"
            );


        const approveDate =
            document.getElementById(
                "approveTargetDate"
            );


        if (approveAction) {

            approveAction.value =
                actionValue;

        }


        if (approveOther) {

            approveOther.value =
                customAction;

        }


        if (approveDate) {

            approveDate.value =
                targetDate.value;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    if (type === "reject") {

        const rejectAction =
            document.getElementById(
                "rejectActionTaken"
            );


        const rejectOther =
            document.getElementById(
                "rejectOtherAction"
            );


        const rejectDate =
            document.getElementById(
                "rejectTargetDate"
            );


        if (rejectAction) {

            rejectAction.value =
                actionValue;

        }


        if (rejectOther) {

            rejectOther.value =
                customAction;

        }


        if (rejectDate) {

            rejectDate.value =
                targetDate.value;

        }

    }


    return true;

}



/*
|--------------------------------------------------------------------------
| APPROVE CONFIRMATION
|--------------------------------------------------------------------------
*/

function confirmApprove() {

    if (
        !setCorrectiveActionValues(
            "approve"
        )
    ) {

        return false;

    }


    return confirm(
        "Are you sure you want to approve this inspection?\n\n" +
        "This action will change the inspection status to Approved."
    );

}



/*
|--------------------------------------------------------------------------
| REJECT CONFIRMATION
|--------------------------------------------------------------------------
*/

function confirmReject() {

    if (
        !setCorrectiveActionValues(
            "reject"
        )
    ) {

        return false;

    }


    return confirm(
        "Are you sure you want to reject this inspection?\n\n" +
        "This action will change the inspection status to Rejected."
    );

}