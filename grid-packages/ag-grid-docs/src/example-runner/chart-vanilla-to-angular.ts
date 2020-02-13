import { getFunctionName } from './chart-vanilla-src-parser';

function removeFunctionKeyword(code): string {
    return code.replace(/^function /, '');
}

const toInput = (property: any) => `[${property.name}]="${property.name}"`;
const toConst = (property: any) => `[${property.name}]="${property.value}"`;
const toMember = (property: any) => `private ${property.name};`;
const toAssignment = (property: any) => `this.${property.name} = ${property.value}`;

function isInstanceMethod(instance: any, property: any) {
    return instance.map(getFunctionName).filter(name => name === property.name).length > 0;
}

function getImports(): string[] {
    return [
        "import { Component } from '@angular/core';",
        "import * as agCharts from 'ag-charts-community';",
        "import { AgChartOptions } from 'ag-charts-angular';",
    ];
}

function getTemplate(bindings: any, attributes: string[]): string {
    const agChartTag = `<ag-charts-angular
    ${attributes.join('\n    ')}
    ></ag-charts-angular>`;

    let template = agChartTag;

    if (bindings.template) {
        template = bindings.template.replace(/\(event\)/g, '($event)').replace('$$CHART$$', agChartTag);
    }

    return template;
}

export function vanillaToAngular(bindings: any, componentFileNames: string[]): string {
    const { properties } = bindings;
    const imports = getImports();
    const propertyAttributes = [];
    const propertyVars = [];
    const propertyAssignments = [];

    properties.forEach(property => {
        if (componentFileNames.length > 0 && property.name === 'components') {
            property.name = 'frameworkComponents';
        }

        if (property.value === 'true' || property.value === 'false') {
            propertyAttributes.push(toConst(property));
        } else if (property.value === null || property.value === 'null') {
            propertyAttributes.push(toInput(property));
        } else {
            // for when binding a method
            // see javascript-grid-keyboard-navigation for an example
            // tabToNextCell needs to be bound to the angular component
            if (!isInstanceMethod(bindings.instance, property)) {
                propertyAttributes.push(toInput(property));
                propertyVars.push(toMember(property));
            }

            propertyAssignments.push(toAssignment(property));
        }
    });

    const instance = bindings.instance.map(removeFunctionKeyword);
    const template = getTemplate(bindings, propertyAttributes);

    return `
${imports.join('\n')}

@Component({
    selector: 'my-app',
    template: \`${template}\`
})

export class AppComponent {
    private options: AgChartOptions;
    ${propertyVars.filter(p => p.name === 'options').join('\n')}

    constructor() {
        ${propertyAssignments.join(';\n')}
    }

    ${instance.map(snippet => snippet.trim()).join('\n\n')}
}

${bindings.globals.join('\n')}
`;
}

if (typeof window !== 'undefined') {
    (<any>window).vanillaToAngular = vanillaToAngular;
}