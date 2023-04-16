<template>
    <div class="bg-white shadow rounded px-3 pt-1 pb-2 border border-white" :data-project-id="project.id">
        <h2 class="text-lg font-bold mb-1 cursor-pointer" @click="openViewer">{{ project.business_name }}</h2>

        <div class="flex items-center text-gray-600 text-xs pb-1 mb-2 border-b border-slate-200">
            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            <span class="font-bold">{{ project.submitted_on }}</span>
        </div>

        <ul class="text-gray-600 text-sm mb-2">
            <li class="flex items-center mb-1">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                <span class="truncate">{{ project.contact_name }}</span>
            </li>
            <li class="flex items-center">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"></circle><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path></svg>
                <span class="truncate">{{ project.email_address }}</span>
            </li>
        </ul>

        <ul class="text-gray-600 text-sm mb-2">
            <li class="flex items-center mb-1">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                <div class="px-3 h-6 rounded-full text-xs font-semibold flex items-center" :class="`bg-blue-100 text-blue-700`">
                    <span>{{ project.project_type }}</span>
                </div>
            </li>
            <li class="flex items-center">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                <span class="truncate">{{ project.budget_range }}</span>
            </li>
        </ul>

        <stack name="inline-editor" narrow v-if="isViewing" @closed="isViewing = false">
            <div class="h-full overflow-auto p-4 bg-grey-30">
                <div v-if="loading" class="absolute inset-0 z-200 flex items-center justify-center text-center">
                    <loading-graphic />
                </div>

                <div v-if="!loading" class="flex items-center mb-3 -mt-1">
                    <h1 class="flex-1">
                        {{ project.business_name }}
                    </h1>
                    <button class="text-grey-70 hover:text-grey-80 mr-3 text-sm"
                            @click.prevent="$event => isViewing = false"
                            v-text="__('Cancel')"></button>
                    <a v-if="apiDetails != null" class="btn-primary ml-1" :href="apiDetails.edit_url" v-text="__('Edit')" />
                </div>

                <div v-if="apiDetails != null">
                    <div class="card rounded">
                        <div class="flex items-center text-gray-600 text-xs pb-1 mb-2 border-b border-slate-200">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            <span class="font-bold">{{ project.submitted_on }}</span>
                        </div>

                        <ul class="text-gray-600 text-sm mb-2">
                            <li class="flex items-center mb-1">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                <span class="truncate">{{ project.contact_name }}</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"></circle><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path></svg>
                                <span class="truncate">{{ project.email_address }}</span>
                            </li>
                        </ul>
                        <ul class="text-gray-600 text-sm mb-2">
                            <li class="flex items-center mb-1">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                                <span class="truncate">{{ project.project_type }}</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                <span class="truncate">{{ project.budget_range }}</span>
                            </li>
                        </ul>

                        <h3 class="text-md font-bold mb-1 pb-1 mb-2 border-b border-slate-200">{{ __('site.additional_information') }}</h3>
                        <div class="prose">
                            {{ apiDetails.additional_information }}
                        </div>
                    </div>
                </div>
            </div>
        </stack>
    </div>
</template>
<script>
export default {
    props: {
        project: {
            type: Object,
            default: () => ({})
        }
    },
    methods: {
        openViewer() {
            this.loading = true;
            this.isViewing = true;

            Statamic.$axios.post(Statamic.cp_url('get-project-details'), {
                projectId: this.project.id
            }).then(response => {
                this.apiDetails = response.data.project;
                this.loading = false;
            });
        }
    },
    data() {
        return {
            isViewing: false,
            loading: false,
            apiDetails: null
        }
    }
};
</script>
