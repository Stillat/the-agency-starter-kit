/**
 * When extending the control panel, be sure to uncomment the necessary code for your build process:
 * https://statamic.dev/extending/control-panel
 */

import ProjectBoard from './components/ProjectBoard.vue';

Statamic.booting(() => {
    Statamic.$components.register('project-board', ProjectBoard);
});
