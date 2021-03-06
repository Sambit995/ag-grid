// Type definitions for @ag-grid-community/core v23.2.1
// Project: http://www.ag-grid.com/
// Definitions by: Niall Crosby <https://github.com/ag-grid/>
import { ColGroupDef } from "../../entities/colDef";
import { ColumnGroup } from "../../entities/columnGroup";
import { DragItem, DropTarget } from "../../dragAndDrop/dragAndDropService";
import { AbstractHeaderWrapper } from "../header/abstractHeaderWrapper";
import { Beans } from "../../rendering/beans";
export declare class HeaderGroupWrapperComp extends AbstractHeaderWrapper {
    private static TEMPLATE;
    private gridOptionsWrapper;
    private columnController;
    private horizontalResizeService;
    private dragAndDropService;
    private userComponentFactory;
    protected beans: Beans;
    private gridApi;
    private columnApi;
    private readonly dragSourceDropTarget;
    protected readonly column: ColumnGroup;
    protected readonly pinned: string;
    private eHeaderCellResize;
    private resizeCols;
    private resizeStartWidth;
    private resizeRatios;
    private resizeTakeFromCols;
    private resizeTakeFromStartWidth;
    private resizeTakeFromRatios;
    private removeChildListenersFuncs;
    constructor(columnGroup: ColumnGroup, dragSourceDropTarget: DropTarget, pinned: string);
    protected postConstruct(): void;
    protected onFocusIn(e: FocusEvent): void;
    protected handleKeyDown(e: KeyboardEvent): void;
    private setupMovingCss;
    getComponentHolder(): ColGroupDef;
    getTooltipText(): string | undefined;
    private setupTooltip;
    private onColumnMovingChanged;
    private addAttributes;
    private appendHeaderGroupComp;
    private afterHeaderCompCreated;
    private addClasses;
    private setupMove;
    getDragItemForGroup(): DragItem;
    private isSuppressMoving;
    private setupWidth;
    private onDisplayedChildrenChanged;
    private addListenersToChildrenColumns;
    private removeListenersOnChildrenColumns;
    private onWidthChanged;
    private setupResize;
    onResizeStart(shiftKey: boolean): void;
    onResizing(finished: boolean, resizeAmount: any): void;
    private normaliseDragChange;
}
