<template>
    <div class="flex">
        <div class="min-h-full flex overflow-x-auto">
            <div v-for="column in projectData" :data-project-status="column.status"
                 :key="column.title"
                 class="bg-gray-100 rounded-md p-2 column-width rounded mr-2">
                <p class="text-gray-700 font-semibold font-sans tracking-wide text-sm">{{column.title}}</p>
                <draggable :list="column.projects" :animation="200" ghost-class="ghost-card" group="projects" @end="handleEnd">
                    <project-card
                        v-for="(project) in column.projects"
                        :key="project.id"
                        :project="project"
                        class="mt-3 cursor-move"
                    ></project-card>
                </draggable>
            </div>
        </div>
    </div>
</template>

<script>
import draggable from "vuedraggable";
import ProjectCard from "./ProjectCard";
export default {
    props: {
        columns: {
            type: Array,
            default: () => ([])
        }
    },
    components: {
        ProjectCard,
        draggable
    },
    methods: {
        reloadProjects() {
            Statamic.$axios.get(Statamic.cp_url('get-projects')).then(response => {
                if (response.status === 200) {
                    this.projectData = response.data.projects;
                }
            });
        },
        handleEnd(e) {
            if (e.to === e.from) {
                return;
            }

            const newStatus = e.to.closest('[data-project-status]').getAttribute('data-project-status'),
                projectId = e.item.getAttribute('data-project-id');

            Statamic.$axios.post(Statamic.cp_url('update-project'), {
                projectId: projectId,
                newStatus: newStatus
            }).then(response => {
                this.reloadProjects();
                Statamic.$toast.success(__('site.project_status_updated'));
            }).catch(error => {
                Statamic.$toast.success(__('site.project_status_update_failed'));
            });
        }
    },
    data() {
        return {
            projectData: []
        };
    },
    created() {
        this.projectData = this.columns;
    }
};
</script>

<style scoped>
    .column-width {
        min-width: 320px;
        width: 320px;
    }
    .ghost-card {
        @apply border opacity-50 border-blue-500 bg-gray-200
    }
</style>
