#!/usr/bin/env bash
set -euo pipefail

# Install shellcheck for shell script linting (used by actionlint)
echo "Checking for shellcheck..."
if ! command -v shellcheck &> /dev/null; then
    echo "shellcheck is not installed. Installing shellcheck..."

    if command -v apt-get &> /dev/null; then
        apt-get install -y shellcheck
    else
        echo "Error: apt package manager is not available. Cannot install shellcheck."
        exit 1
    fi

    if ! command -v shellcheck &> /dev/null; then
        echo "Error: Failed to install shellcheck."
        exit 1
    fi

    echo "shellcheck installed successfully!"
else
    echo "shellcheck is already available."
fi

# Install actionlint for GitHub Actions workflow linting
echo "Checking for actionlint..."
if ! command -v actionlint &> /dev/null && [ ! -f "./actionlint" ]; then
    echo "actionlint is not installed. Downloading actionlint..."
    bash <(curl https://raw.githubusercontent.com/rhysd/actionlint/v1.7.9/scripts/download-actionlint.bash) 1.7.9
    echo "actionlint installed successfully!"
else
    echo "actionlint is already available."
fi
