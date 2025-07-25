<div class="">
    <div class="flex flex-col justify-center pb-4 text-sm select-text">
        <div class="flex gap-2  md:flex-row flex-col pt-4">
            <x-forms.input label="Source Path" :value="$fileStorage->fs_path" readonly />
            <x-forms.input label="Destination Path" :value="$fileStorage->mount_path" readonly />
        </div>
    </div>
    <form wire:submit='submit' class="flex flex-col gap-2">
        <div class="flex gap-2">
            @if ($fileStorage->is_directory)
                <x-modal-confirmation :ignoreWire="false" title="Confirm Directory Conversion to File?"
                    buttonTitle="Convert to file" submitAction="convertToFile" :actions="[
                        'All files in this directory will be permanently deleted and an empty file will be created in its place.',
                    ]"
                    confirmationText="{{ $fs_path }}"
                    confirmationLabel="Please confirm the execution of the actions by entering the Filepath below"
                    shortConfirmationLabel="Filepath" :confirmWithPassword="false" step2ButtonText="Convert to file" />
                <x-modal-confirmation :ignoreWire="false" title="Confirm Directory Deletion?" buttonTitle="Delete"
                    isErrorButton submitAction="delete" :checkboxes="$directoryDeletionCheckboxes" :actions="[
                        'The selected directory and all its contents will be permanently deleted from the container.',
                    ]"
                    confirmationText="{{ $fs_path }}"
                    confirmationLabel="Please confirm the execution of the actions by entering the Filepath below"
                    shortConfirmationLabel="Filepath" />
            @else
                @if (!$fileStorage->is_binary)
                    <x-modal-confirmation :ignoreWire="false" title="Confirm File Conversion to Directory?"
                        buttonTitle="Convert to directory" submitAction="convertToDirectory" :actions="[
                            'The selected file will be permanently deleted and an empty directory will be created in its place.',
                        ]"
                        confirmationText="{{ $fs_path }}"
                        confirmationLabel="Please confirm the execution of the actions by entering the Filepath below"
                        shortConfirmationLabel="Filepath" :confirmWithPassword="false" step2ButtonText="Convert to directory" />
                @endif
                <x-forms.button type="button" wire:click="loadStorageOnServer">Load from server</x-forms.button>
                <x-modal-confirmation :ignoreWire="false" title="Confirm File Deletion?" buttonTitle="Delete"
                    isErrorButton submitAction="delete" :checkboxes="$fileDeletionCheckboxes" :actions="['The selected file will be permanently deleted from the container.']"
                    confirmationText="{{ $fs_path }}"
                    confirmationLabel="Please confirm the execution of the actions by entering the Filepath below"
                    shortConfirmationLabel="Filepath" />
            @endif
        </div>
        @if (!$fileStorage->is_directory)
            @if (data_get($resource, 'settings.is_preserve_repository_enabled'))
                <div class="w-96">
                    <x-forms.checkbox instantSave label="Is this based on the Git repository?"
                        id="fileStorage.is_based_on_git"></x-forms.checkbox>
                </div>
            @endif
            <x-forms.textarea
                label="{{ $fileStorage->is_based_on_git ? 'Content (refreshed after a successful deployment)' : 'Content' }}"
                rows="20" id="fileStorage.content"
                readonly="{{ $fileStorage->is_based_on_git || $fileStorage->is_binary }}"></x-forms.textarea>
            @if (!$fileStorage->is_based_on_git && !$fileStorage->is_binary)
                <x-forms.button class="w-full" type="submit">Save</x-forms.button>
            @endif
        @endif
    </form>
</div>
