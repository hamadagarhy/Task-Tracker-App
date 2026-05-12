import './bootstrap';
import {initTaskCompletionHandlers} from "./tasks.js";
import { initAjaxListHandlers } from "./ajax-list.js";
import { initPreventDoubleSubmit } from "./prevent-double-submit.js";

initTaskCompletionHandlers()
initAjaxListHandlers()
initPreventDoubleSubmit()
