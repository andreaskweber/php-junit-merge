#!/bin/bash

export DEBIAN_FRONTEND=noninteractive
BASE_PATH='/vagrant'

echo '[provision] Starting shell provisioning';

if [[ ! -d '/.provision' ]]; then
    mkdir '/.provision'
    echo '[provision] Created directory /.provision'
fi

#
# Base packages
#

if [[ ! -f '/.provision/base-packages' ]]; then

    echo '[base-packages] Running apt-get update'
    apt-get update >/dev/null
    echo '[base-packages] Finished running apt-get update'

    echo '[base-packages] Installing git'
    apt-get -q -y install git-core >/dev/null
    echo '[base-packages] Finished installing git'

    echo '[base-packages] Installing rubygems'
    apt-get install -y rubygems >/dev/null
    echo '[base-packages] Finished installing rubygems'

    echo '[base-packages] Installing base packages for r10k'
    apt-get install -y build-essential ruby-dev >/dev/null
    gem install json >/dev/null
    echo '[base-packages] Finished installing base packages for r10k'

    echo '[base-packages] Installing r10k'
    gem install r10k >/dev/null 2>&1
    echo '[base-packages] Finished installing r10k'

    touch '/.provision/base-packages'
    echo '[base-packages] Provision complete'

fi

#
# Puppet
#

if [[ ! -f '/.provision/puppet' ]]; then

    CODENAME='wheezy'

    echo "[puppet] Downloading http://apt.puppetlabs.com/puppetlabs-release-${CODENAME}.deb"
    wget --quiet --tries=5 --connect-timeout=10 -O "/.provision/puppetlabs-release-${CODENAME}.deb" "http://apt.puppetlabs.com/puppetlabs-release-${CODENAME}.deb"
    echo "[puppet] Finished downloading http://apt.puppetlabs.com/puppetlabs-release-${CODENAME}.deb"

    dpkg -i "/.provision/puppetlabs-release-${CODENAME}.deb" >/dev/null

    echo '[puppet] Running apt-get update'
    apt-get update >/dev/null
    echo '[puppet] Finished running apt-get update'

    echo '[puppet] Updating Puppet to version 3.4.x'
    apt-get install -y puppet-common=3.4.* puppet=3.4.* >/dev/null
    apt-mark hold puppet puppet-common >/dev/null
    PUPPET_VERSION=$(puppet help | grep 'Puppet v')
    echo "[puppet] Finished updating puppet to latest version: ${PUPPET_VERSION}"

    touch '/.provision/puppet'
    echo '[puppet] Provision complete'

fi

#
# R10K
#

echo '[R10K] Running puppet modules installation/update'
cd "${BASE_PATH}/resources/vagrant/puppet" && r10k --verbose INFO puppetfile install >/dev/null
echo '[R10K] Finished running puppet modules update'

#
# Local Modules
#

cd "${BASE_PATH}/resources/vagrant/puppet" && cp -R modules-local/* modules >/dev/null
echo '[puppet] Copied local modules to puppet module directory'

#
# Finish provisioning
#

echo '[provision] Shell provisioning complete';
