/// <reference path="app.d.ts" />
module KnightsInSpace {
    export function matchHeight(element: any) {
        $(element).height($(element).width() / 16 * 9);
    }
}
