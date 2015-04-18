#
# Environment
#

VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

    config.vm.box = "chef/debian-7.4"
    config.vm.box_url = "chef/debian-7.4"
    config.ssh.forward_agent = true

    config.vm.provider :virtualbox do |virtualbox|
        virtualbox.customize ["modifyvm", :id, "--memory", 1024]
    end

    config.vm.provision "shell", path: "resources/vagrant/provision.sh"
    config.vm.provision :puppet do |puppet|
        puppet.manifests_path = "resources/vagrant/puppet/manifests"
        puppet.module_path = "resources/vagrant/puppet/modules"
        puppet.manifest_file = "site.pp"
        puppet.hiera_config_path = "resources/vagrant/puppet/hiera.yaml"
        puppet.options = "--parser future"
    end

    config.vm.define "core" do |core|
        core.vm.host_name = "php-junit-merge.andreas-weber.dev"
        core.vm.network "private_network", ip: "192.168.49.43"

        core.vm.provider :virtualbox do |virtualbox|
            virtualbox.customize ["modifyvm", :id, "--name", "php-junit-merge.andreas-weber.dev"]
        end
    end

end
