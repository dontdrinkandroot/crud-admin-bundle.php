# AGENTS.md

## Instructions

* We use a sober and scientific style.
* Get back to the user: When seemingly stuck, when an approach does not work as expected, or when new decisions have to be taken, the LLM Agent MUST stop and get back to the user with the situation and options instead of continuing with assumptions. Do not silently pivot to a different approach.

## Coding Rules

* We always adhere to Clean Code and SOLID principles. Keep in mind that this avoids unnecessary comments and rather uses speaking variable and function names.

## Verification

At the end of any task, the agent **must** run `bin/validate`. A successful run of `bin/validate` is the definition of done for any change.

## Self-Update Instruction

This guidelines file is a living document and MUST be actively maintained by the LLM Agent.

* **Trigger:** Whenever significant changes are made to the tech stack, project structure, coding guidelines, or key features, the LLM Agent MUST immediately update this file (`AGENTS.md`) to reflect the current state of the project.
* **Content:** 
    * Add any information that could have helped the agent to solve the task more efficiently or in fewer steps.
    * Remove outdated, obsolete, or incorrect information.
    * Ensure all tech stack versions and library names are accurate.
    * Make sure the most important features are clearly documented.
    * Keep the project structure up to date so that the most important files and directories are visible at a glance.
* **Proactivity:** Do not wait for explicit instructions to update these guidelines if you identify a discrepancy between the guidelines and the actual codebase.
